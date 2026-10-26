<?php

return [

    'registration_slip' => [
        'office' => 'OFFICE OF THE TRAFFIC MANAGEMENT SECTION',
        'title' => 'FRANCHISE REGISTRATION',

        // amount = null prints the label with no amount (e.g. Police Clearance)
        'fees' => [
            ['label' => 'Police Clearance', 'amount' => null],
            ['label' => 'Registration Fee (Tricycle)', 'amount' => 400],
            ['label' => 'Issuance of Body Number', 'amount' => 300],
            ['label' => 'Barangay Clearance', 'amount' => 100],
            ['label' => "Mayor's Permit", 'amount' => 100],
            ['label' => 'Garbage Fee (Tricycle)', 'amount' => 720],
            ['label' => 'Franchise Fee Motorize Vehicle', 'amount' => 50],
            ['label' => 'Documentary Stamp', 'amount' => 30],
            ['label' => 'LOMT', 'amount' => 130],
        ],

        'secretary' => [
            'name' => 'JAMAICA L. DELFIN',
            'title' => 'TMS-SECRETARY',
        ],
        'officer' => [
            'name' => 'PLTCOL MARGARITO A. SALAÑO (RET)',
            'title' => 'CIVIL SECURITY OFFICER',
        ],
    ],

    'license' => [
        'ordinance_no' => '465-190922',
        'default_issued_at' => 'Civil Security Office Palompon, Leyte',

        'assessed_by' => [
            'name' => 'PLTCOL MARGARITO A. SALAÑO (RET)',
            'title' => 'SECURITY OFFICER',
        ],
        'recommending_approval' => [
            'name' => 'AIME D. MACAYAN',
            'title' => 'LLSE I / TEMP. SB SECRETARY',
        ],
        'approved_by' => [
            'name' => 'JAVES KEITH R. DE LA CALZADA',
            'title' => 'MUNICIPAL VICE MAYOR',
        ],
    ],

];