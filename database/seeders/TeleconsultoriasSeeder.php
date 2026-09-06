<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Enums\TeleconsultoriaStatus;
use App\Models\Service;
use App\Models\Teleconsultoria;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Popula a tabela de teleconsultorias com um volume massivo de registros.
 *
 * Usa DB::table()->insert() em lotes, em vez de Teleconsultoria::create(),
 * para evitar o overhead de hidratação de Model, eventos e observers em
 * cada linha — essencial para performance em escala de milhões.
 *
 * Uso:
 *   php artisan db:seed --class=TeleconsultoriaSeeder
 *
 * Para volumes muito grandes, rode sem limite de memória:
 *   php -d memory_limit=-1 artisan db:seed --class=TeleconsultoriaSeeder
 */
final class TeleconsultoriasSeeder extends Seeder
{
    /** Total de registros a inserir. Ajuste conforme a necessidade. */
    private const TOTAL_ROWS = 5_000_000;

    /**
     * Linhas por lote de INSERT.
     * 2000 linhas x ~11 colunas fica bem abaixo do limite de parâmetros
     * do protocolo do Postgres (65535) e mantém o uso de memória constante
     * independente do TOTAL_ROWS.
     */
    private const CHUNK_SIZE = 2000;

    /** Quantos UUIDs de FK manter em cache para sorteio em memória. */
    private const FK_POOL_SIZE = 5000;

    private const DIAGNOSTIC_HYPOTHESES = [
        'Hipertensão arterial sistêmica não controlada',
        'Diabetes mellitus tipo 2 descompensado',
        'Suspeita de infarto agudo do miocárdio',
        'Dor torácica atípica em investigação',
        'Insuficiência renal crônica em estágio avançado',
        'Pneumonia adquirida na comunidade',
        'Crise convulsiva de etiologia a esclarecer',
        'Suspeita de acidente vascular cerebral isquêmico',
        'Descompensação de insuficiência cardíaca',
        'Quadro depressivo moderado a grave',
        'Suspeita de apendicite aguda',
        'Asma brônquica em exacerbação',
        'Dermatose de etiologia indeterminada',
        'Cefaleia de forte intensidade a esclarecer',
        'Suspeita de infecção urinária complicada',
    ];

    private const CLINICAL_HISTORY_SNIPPETS = [
        'Paciente refere início dos sintomas há cerca de 3 dias, com piora progressiva.',
        'Histórico de comorbidades prévias, em acompanhamento irregular na atenção básica.',
        'Nega alergias medicamentosas conhecidas. Faz uso contínuo de medicação de rotina.',
        'Já apresentou quadro semelhante há alguns meses, sem investigação conclusiva.',
        'Encaminhado pela UBS de origem por dificuldade de manejo em nível local.',
        'Familiar relata piora do quadro nas últimas 24 horas, com necessidade de avaliação urgente.',
        'Sem histórico de internações recentes. Realiza acompanhamento ambulatorial esporádico.',
        'Exames laboratoriais prévios sem alterações significativas relatadas pelo paciente.',
    ];

    private const PROFESSIONAL_OPINIONS = [
        'Sugiro manter conduta atual e reavaliar em 48h caso não haja melhora.',
        'Recomendo encaminhamento para avaliação presencial com especialista o quanto antes.',
        'Orientações fornecidas ao solicitante quanto a sinais de alarme e retorno precoce.',
        'Conduta ajustada conforme protocolo local; solicito retorno para seguimento.',
        'Diante do quadro descrito, recomendo investigação complementar antes da conclusão diagnóstica.',
    ];

    /** @var list<string> */
    private array $solicitanteUuids = [];

    /** @var list<string> */
    private array $serviceUuids = [];

    /** @var list<string> */
    private array $statusValues = [];

    public function run(): void
    {
        // Evita que o Query Log do Laravel acumule cada statement em
        // memória — sem isso, seeders longos costumam estourar o limite
        // de memória bem antes de terminar.
        DB::connection()->disableQueryLog();

        $this->loadForeignKeyPools();

        $faker = FakerFactory::create('pt_BR');

        $table = (new Teleconsultoria)->getTable();

        $output = $this->command?->getOutput();
        $bar    = $output?->createProgressBar(self::TOTAL_ROWS);
        $bar?->start();

        $inserted = 0;

        while ($inserted < self::TOTAL_ROWS) {
            $batchSize = min(self::CHUNK_SIZE, self::TOTAL_ROWS - $inserted);

            $rows = [];

            for ($i = 0; $i < $batchSize; $i++) {
                $rows[] = $this->buildRow($faker);
            }

            DB::table($table)->insert($rows);

            $inserted += $batchSize;
            $bar?->advance($batchSize);
        }

        $bar?->finish();
        $output?->writeln("\n{$inserted} registros inseridos em '{$table}'.");
    }

    private function loadForeignKeyPools(): void
    {
        $this->solicitanteUuids = User::query()
            ->role(RoleName::SOLICITANTE->value)
            ->inRandomOrder()
            ->limit(self::FK_POOL_SIZE)
            ->pluck('uuid')
            ->all();

        $this->serviceUuids = Service::query()
            ->inRandomOrder()
            ->limit(self::FK_POOL_SIZE)
            ->pluck('uuid')
            ->all();

        if ($this->solicitanteUuids === [] || $this->serviceUuids === []) {
            throw new RuntimeException(
                'É preciso existir ao menos um User com role solicitante e um Service '
                .'cadastrados antes de rodar este seeder (rode os seeders de User/Service primeiro).'
            );
        }

        $this->statusValues = array_map(
            static fn (TeleconsultoriaStatus $status): string => $status->value,
            TeleconsultoriaStatus::cases(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function buildRow(FakerGenerator $faker): array
    {
        $now = now();

        return [
            // Ajuste o nome da coluna aqui caso a trait HasUuid use um
            // nome diferente de 'uuid' para a chave primária da tabela.
            'uuid'                  => Str::uuid()->toString(),
            'solicitante_uuid'      => $faker->randomElement($this->solicitanteUuids),
            'service_uuid'          => $faker->randomElement($this->serviceUuids),
            'patient_name'          => $faker->name(),
            'patient_birthday'      => $faker->dateTimeBetween('-90 years', '-1 years')->format('Y-m-d'),
            'diagnostic_hypothesis' => $faker->randomElement(self::DIAGNOSTIC_HYPOTHESES),
            'clinical_history'      => implode(' ', $faker->randomElements(self::CLINICAL_HISTORY_SNIPPETS, 2)),
            'professional_opinion'  => $faker->boolean(70)
                ? $faker->randomElement(self::PROFESSIONAL_OPINIONS)
                : null,
            'status'     => $faker->randomElement($this->statusValues),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
