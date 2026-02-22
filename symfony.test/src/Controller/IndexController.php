<?php
namespace App\Controller;
use Throwable;
use App\Service\GenAiService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class IndexController extends AbstractController
{
    public const NO_NEWS = 'No News [%d]';
    public const ERR_API = 'Unable to make API call';
    public const FMT_HTML = 'html';
    public const FMT_JSON = 'json';
    public const CATEGORIES = ['World','North America','South America','Europe','Middle East','Asia','South Pacific'];
    public const DEFAULT_CATEGORY = 'World';
    public const PROMPT = 'In JSON format, give me headlines and summaries of the top 10 news stories in this category: %s.'
                        . 'For each item only give me these 3 things:'
                        . 'headline: The headline of the news item with a link to the news story.'
                        . 'summary: A concise 50 word summary of the news story.'
                        . 'link: A URL link to the news story.'
                        . 'Do not provide any other explanations or reasoning -- just give me the JSON string with "headline", "summary", and "link" for each news item.';
    #[Route('/', name: 'app_news', methods: ['GET'])]
    public function index(Request $request, GenAiService $service): Response
    {
        return $this->render(
            'index.html.twig', [
                'categories' => self::CATEGORIES, 
                'news' => $this->getChat(self::DEFAULT_CATEGORY, $service, self::FMT_HTML)
            ]
        );
    }
    #[Route('/api/news', name: 'app_news_api', methods: ['POST'])]
    public function newsAjax(Request $request, GenAiService $service): string
    {
        $data = json_decode($request->getContent(), true);
        $category = strip_tags($data['category'] ?? self::DEFAULT_CATEGORY);
        // Validation
        if (empty($category) || !in_array($category, self::CATEGORIES)) 
            return $this->json(['success' => false,'error' => 'Missing required fields'], 400);
        return $this->getChat($category, $service, self::FMT_JSON);
    }
    protected function getChat(string $category, GenAiService $service, $format = 'json') : string
    {
        try {
            $prompt   = sprintf(self::PROMPT, $category);
            $response = $service->chat($prompt);
            $json     = trim($response->getResult()->getContent() ?? '');
            $json     = str_replace(['```json','```'], '', $json);
            $arr      = json_decode($json, TRUE);
            $html     = sprintf(self::NO_NEWS, __LINE__);
            if (!empty($arr)) {
                $html = '<table>';
                $html .= '<tr><th>Headline</th><th>Summary</th></tr>';
                foreach ($arr as $item) {
                    $html .= '<tr>';
                    $html .= '<td><a href="' . ($item['link'] ?? '') . '">' . ($item['headline'] ?? 'No Headline') . '</a></td>';
                    $html .= '<td>' . ($item['summary'] ?? 'No Summary') . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                if ($format === self::FMT_HTML) {
                    $response = $html;
                } else {
                    $response = $this->json(['success' => true,'html' => $html]);
                }
            }
        } catch (Throwable $e) {
            if ($format === self::FMT_HTML) {
                $response = sprintf(self::NO_NEWS, __LINE__);
            } else {
                $response = $this->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        }
        return $response;
    }
}
