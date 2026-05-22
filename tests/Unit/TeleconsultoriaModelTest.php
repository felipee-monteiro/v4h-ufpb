<?php

use App\Models\Teleconsultoria;

test('teleconsultoria model uses the teleconsultorias table', function (): void {
    $teleconsultoria = new Teleconsultoria();

    expect($teleconsultoria->getTable())->toBe('teleconsultorias')
        ->and($teleconsultoria->getKeyName())->toBe('uuid');
});
