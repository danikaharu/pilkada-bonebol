<?php

namespace App\Exports;

use App\Models\Polling;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PollingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $type;
    private $row = 0;

    function __construct($type)
    {
        $this->type = $type;
    }

    public function model()
    {
        ++$this->row;
    }

    public function collection()
    {
        $data = Polling::where('type', $this->type)->get();

        return $data;
    }

    /**
     * @var Polling $polling
     */
    public function map($polling): array
    {
        $votes = json_decode($polling->candidate_votes, true);
        $rowData = [
            ++$this->row,
            $polling->polling_station->village->subdistrict->electoral_district->name,
            $polling->polling_station->village->subdistrict->name,
            $polling->polling_station->village->name,
            $polling->polling_station->name,
        ];

        foreach ($votes as $vote) {
            $rowData[] = $vote;
        }

        $rowData[] = $polling->invalid_votes;

        return $rowData;
    }

    public function headings(): array
    {
        $headings = [
            'NO.',
            'DAPIL',
            'KECAMATAN',
            'KELURAHAN/DESA',
            'TPS',
        ];

        $polling = Polling::where('type', $this->type)->first();

        if ($polling) {
            // Decode JSON votes
            $votes = json_decode($polling->candidate_votes, true);
            $numPaslon = count($votes); // Hitung jumlah paslon

            // Generate dynamic headings for the number of paslon found
            for ($i = 0; $i < $numPaslon; $i++) {
                $headings[] = "PASLON " . ($i + 1);
            }
        }

        $headings[] = 'SUARA TIDAK SAH';

        return $headings;
    }

    public function startCell(): string
    {
        return 'A2';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $highestColumn = $event->sheet->getHighestColumn();
                $highestRow = $event->sheet->getHighestRow();

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $range = 'A2:' . $highestColumn . $highestRow;
                $event->sheet->getStyle($range)->applyFromArray($styleArray);
            },
        ];
    }
}
