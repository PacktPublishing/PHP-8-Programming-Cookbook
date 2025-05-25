<?php

namespace Cookbook\Chapter10\SearchEngine\PersistenceHandler;

use PDO;

class MysqlHandler implements DataSourceHandlerInterface
{
    private PDO $databaseConnection;
    private string $dataTableName;

    public function prepare(array $options): void
    {
        $dataSourceName = "mysql:host={$options['host']};dbname={$options['dbname']};charset=utf8mb4";
        $this->databaseConnection = new PDO(
            $dataSourceName,
            $options['username'],
            $options['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        $this->dataTableName = $options['table'];
    }

    public function executeSearch(array $searchParameters): array
    {
        $searchTerm = $searchParameters['query'];
        $currentPage = $searchParameters['page'];
        $resultsPerPage = $searchParameters['page_size'];
        $offset = ($currentPage - 1) * $resultsPerPage;

        // Search Query
        $searchSql = "SELECT * FROM {$this->dataTableName}
                    WHERE CONCAT_WS(' ', Make, Model, Year, Price, Mileage, `Condition`, Description)
                    LIKE :searchTerm
                    LIMIT :resultsLimit OFFSET :resultsOffset";

        $searchStatement = $this->databaseConnection->prepare($searchSql);
        $searchStatement->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
        $searchStatement->bindValue(':resultsLimit', (int)$resultsPerPage, PDO::PARAM_INT);
        $searchStatement->bindValue(':resultsOffset', (int)$offset, PDO::PARAM_INT);
        $searchStatement->execute();
        $searchResults = $searchStatement->fetchAll();

        // Get Total Count
        $countSql = "SELECT COUNT(*) FROM {$this->dataTableName}
                    WHERE CONCAT_WS(' ', Make, Model, Year, Price, Mileage, `Condition`, Description)
                    LIKE :searchTerm";
        $countStatement = $this->databaseConnection->prepare($countSql);
        $countStatement->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
        $countStatement->execute();
        $totalResultsCount = $countStatement->fetchColumn();

        return [
            'total_results' => $totalResultsCount,
            'page' => $currentPage,
            'page_size' => $resultsPerPage,
            'results' => $searchResults,
        ];
    }
}