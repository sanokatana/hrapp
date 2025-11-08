<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Cabang;
use App\Models\Company;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\KonfigurasiLokasi;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $defaultPassword = 'password123';

        User::updateOrCreate(
            ['email' => 'admin@hrmschl.local'],
            [
                'nik' => 'ADM001',
                'name' => 'Administrator',
                'password' => Hash::make($defaultPassword),
                'level' => 'Superadmin',
            ]
        );

        $defaultUsers = [
            ['nik' => 'MNG001', 'name' => 'Mira Manager', 'email' => 'mira.manager@hrmschl.local', 'level' => 'Management'],
            ['nik' => 'HRD001', 'name' => 'Hasanah HRD', 'email' => 'hasanah.hrd@hrmschl.local', 'level' => 'HRD'],
            ['nik' => 'ADM002', 'name' => 'Adi Administrator', 'email' => 'adi.admin@hrmschl.local', 'level' => 'Admin'],
            ['nik' => 'SUP001', 'name' => 'Satria Support', 'email' => 'satria.support@hrmschl.local', 'level' => 'Admin'],
        ];

        foreach ($defaultUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'nik' => $userData['nik'],
                    'name' => $userData['name'],
                    'level' => $userData['level'],
                    'password' => Hash::make($defaultPassword),
                ]
            );
        }

        $companiesData = [
            [
                'short_name' => 'CHL',
                'long_name' => 'PT Cipta Harmoni Lestari',
                'branches' => [
                    [
                        'key' => 'JKT',
                        'kode' => 'JKT-01',
                        'nama' => 'Jakarta Headquarters',
                        'alamat' => 'Jl. Jend. Sudirman No. 15',
                        'kota' => 'Jakarta',
                        'location' => [
                            'nama_kantor' => 'Jakarta HQ',
                            'latitude' => -6.21462,
                            'longitude' => 106.84513,
                            'radius' => 200,
                        ],
                    ],
                    [
                        'key' => 'BDG',
                        'kode' => 'BDG-01',
                        'nama' => 'Bandung Operations',
                        'alamat' => 'Jl. Asia Afrika No. 8',
                        'kota' => 'Bandung',
                        'location' => [
                            'nama_kantor' => 'Bandung Office',
                            'latitude' => -6.91746,
                            'longitude' => 107.61912,
                            'radius' => 150,
                        ],
                    ],
                ],
                'departments' => [
                    [
                        'kode' => 'IT-CHL',
                        'nama' => 'Information Technology',
                        'branch_key' => 'JKT',
                        'positions' => [
                            ['nama' => 'Software Engineer', 'level' => 'Senior'],
                            ['nama' => 'IT Support', 'level' => 'Staff'],
                        ],
                    ],
                    [
                        'kode' => 'OPS-CHL',
                        'nama' => 'Operations',
                        'branch_key' => 'BDG',
                        'positions' => [
                            ['nama' => 'Operations Supervisor', 'level' => 'Supervisor'],
                            ['nama' => 'Operations Staff', 'level' => 'Staff'],
                        ],
                    ],
                ],
            ],
            [
                'short_name' => 'CHS',
                'long_name' => 'PT Cakra Harmoni Sejahtera',
                'branches' => [
                    [
                        'key' => 'SBY',
                        'kode' => 'SBY-01',
                        'nama' => 'Surabaya Branch',
                        'alamat' => 'Jl. Pemuda No. 21',
                        'kota' => 'Surabaya',
                        'location' => [
                            'nama_kantor' => 'Surabaya Office',
                            'latitude' => -7.25747,
                            'longitude' => 112.75209,
                            'radius' => 175,
                        ],
                    ],
                    [
                        'key' => 'DPS',
                        'kode' => 'DPS-01',
                        'nama' => 'Denpasar Branch',
                        'alamat' => 'Jl. Raya Puputan No. 88',
                        'kota' => 'Denpasar',
                        'location' => [
                            'nama_kantor' => 'Denpasar Office',
                            'latitude' => -8.67046,
                            'longitude' => 115.21263,
                            'radius' => 150,
                        ],
                    ],
                ],
                'departments' => [
                    [
                        'kode' => 'HR-CHS',
                        'nama' => 'Human Resources',
                        'branch_key' => 'SBY',
                        'positions' => [
                            ['nama' => 'HR Manager', 'level' => 'Manager'],
                            ['nama' => 'Recruitment Specialist', 'level' => 'Staff'],
                        ],
                    ],
                    [
                        'kode' => 'FIN-CHS',
                        'nama' => 'Finance',
                        'branch_key' => 'DPS',
                        'positions' => [
                            ['nama' => 'Finance Analyst', 'level' => 'Senior'],
                            ['nama' => 'Finance Officer', 'level' => 'Staff'],
                        ],
                    ],
                ],
            ],
            [
                'short_name' => 'GDN',
                'long_name' => 'PT Garda Nusantara',
                'branches' => [
                    [
                        'key' => 'MDN',
                        'kode' => 'MDN-01',
                        'nama' => 'Medan Hub',
                        'alamat' => 'Jl. Balai Kota No. 5',
                        'kota' => 'Medan',
                        'location' => [
                            'nama_kantor' => 'Medan Office',
                            'latitude' => 3.59520,
                            'longitude' => 98.67220,
                            'radius' => 160,
                        ],
                    ],
                    [
                        'key' => 'MKS',
                        'kode' => 'MKS-01',
                        'nama' => 'Makassar Hub',
                        'alamat' => 'Jl. Jend. Ahmad Yani No. 12',
                        'kota' => 'Makassar',
                        'location' => [
                            'nama_kantor' => 'Makassar Office',
                            'latitude' => -5.14767,
                            'longitude' => 119.43273,
                            'radius' => 180,
                        ],
                    ],
                ],
                'departments' => [
                    [
                        'kode' => 'SL-GDN',
                        'nama' => 'Sales',
                        'branch_key' => 'MDN',
                        'positions' => [
                            ['nama' => 'Sales Lead', 'level' => 'Lead'],
                            ['nama' => 'Account Executive', 'level' => 'Staff'],
                        ],
                    ],
                    [
                        'kode' => 'CS-GDN',
                        'nama' => 'Customer Service',
                        'branch_key' => 'MKS',
                        'positions' => [
                            ['nama' => 'Customer Success', 'level' => 'Senior'],
                            ['nama' => 'Customer Support', 'level' => 'Staff'],
                        ],
                    ],
                ],
            ],
        ];

        $employeeCounter = 1;

        foreach ($companiesData as $companyData) {
            $company = Company::updateOrCreate(
                ['short_name' => $companyData['short_name']],
                ['long_name' => $companyData['long_name']]
            );

            $branchMap = [];
            $locationMap = [];

            foreach ($companyData['branches'] as $branchData) {
                $branch = Cabang::updateOrCreate(
                    ['kode' => $branchData['kode']],
                    [
                        'company_id' => $company->id,
                        'nama' => $branchData['nama'],
                        'alamat' => $branchData['alamat'],
                        'kota' => $branchData['kota'],
                    ]
                );

                $branchMap[$branchData['key']] = $branch;

                $locationName = $branchData['location']['nama_kantor'] . ' - ' . $company->short_name;
                $location = KonfigurasiLokasi::updateOrCreate(
                    ['nama_kantor' => $locationName],
                    [
                        'latitude' => $branchData['location']['latitude'],
                        'longitude' => $branchData['location']['longitude'],
                        'radius' => $branchData['location']['radius'],
                        'company_id' => $company->id,
                        'cabang_id' => $branch->id,
                    ]
                );

                $locationMap[$branchData['key']] = $location;
            }

            foreach ($companyData['departments'] as $departmentData) {
                $branch = $branchMap[$departmentData['branch_key']] ?? null;

                $department = Department::updateOrCreate(
                    ['kode' => $departmentData['kode']],
                    [
                        'nama' => $departmentData['nama'],
                        'company_id' => $company->id,
                        'cabang_id' => $branch?->id,
                    ]
                );

                foreach ($departmentData['positions'] as $positionData) {
                    $position = Jabatan::updateOrCreate(
                        [
                            'department_id' => $department->id,
                            'nama' => $positionData['nama'],
                        ],
                        [
                            'company_id' => $company->id,
                            'cabang_id' => $branch?->id,
                            'level' => $positionData['level'] ?? null,
                        ]
                    );

                    $this->seedEmployeesForPosition(
                        $position,
                        $company,
                        $branch,
                        $department,
                        $locationMap[$departmentData['branch_key']] ?? null,
                        $employeeCounter,
                        $defaultPassword
                    );
                }
            }
        }
    }

    protected function seedEmployeesForPosition(
        Jabatan $position,
        Company $company,
        ?Cabang $branch,
        Department $department,
        ?KonfigurasiLokasi $location,
        int &$employeeCounter,
        string $defaultPassword
    ): void {
        static $nameIndex = 0;

        $names = [
            'Aldi Prakoso',
            'Bima Saputra',
            'Citra Wulandari',
            'Dewi Anggraeni',
            'Eka Suryani',
            'Fajar Nugroho',
            'Gita Lestari',
            'Hendra Wijaya',
            'Intan Permata',
            'Joko Prasetyo',
            'Kirana Ayu',
            'Lukman Hakim',
        ];

        for ($i = 0; $i < 2; $i++) {
            $name = $names[$nameIndex % count($names)];
            $nameIndex++;

            $nik = sprintf('EMP%04d', $employeeCounter++);

            $emailHost = strtolower($company->short_name) . '.local';
            $email = Str::slug($name, '.') . "@{$emailHost}";

            $employee = Karyawan::updateOrCreate(
                ['nik' => $nik],
                [
                    'nama_lengkap' => $name,
                    'email' => $email,
                    'no_hp' => '08' . \str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT),
                    'tgl_masuk' => Carbon::today()->subMonths(random_int(2, 18))->subDays(random_int(0, 25)),
                    'company_id' => $company->id,
                    'cabang_id' => $branch?->id,
                    'department_id' => $department->id,
                    'jabatan_id' => $position->id,
                    'lokasi_id' => $location?->id,
                    'status_kar' => 'Aktif',
                    'password' => Hash::make($defaultPassword),
                ]
            );

            $this->seedPresensiForEmployee($employee, $location);
        }
    }

    protected function seedPresensiForEmployee(Karyawan $employee, ?KonfigurasiLokasi $location): void
    {
        for ($offset = 0; $offset < 10; $offset++) {
            $date = Carbon::today()->subDays($offset);

            if ($date->isWeekend()) {
                continue;
            }

            $jamMasuk = Carbon::createFromTime(8, random_int(0, 20))->format('H:i:s');
            $jamKeluar = Carbon::createFromTime(17, random_int(0, 20))->format('H:i:s');

            Presensi::updateOrCreate(
                [
                    'karyawan_id' => $employee->id,
                    'tanggal' => $date->toDateString(),
                ],
                [
                    'lokasi_id' => $location?->id,
                    'jam_masuk' => $jamMasuk,
                    'jam_keluar' => $jamKeluar,
                ]
            );
        }
    }
}
