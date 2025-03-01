<?php

try {
    // DateInvalidTimeZoneException
    $dateWithInvalidTimezone = new DateTime('now', new DateTimeZone('ABC123/Timezone'));

    // DateMalformedStringException
    $malformedDateString = new DateTime('invalid_date_format');

    // DateMalformedIntervalStringException
    $interval = DateInterval::createFromDateString('invalid@invalid');
} catch (DateInvalidTimeZoneException $exception) {
    echo "DateInvalidTimeZoneException: " . $exception->getMessage();
} catch (DateMalformedStringException $exception) {
    echo "DateMalformedStringException: " . $exception->getMessage();
} catch (DateMalformedIntervalStringException $exception) {
    echo "DateMalformedIntervalStringException: " . $exception->getMessage();
}

?>