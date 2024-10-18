<?php
    return [
        'required' => 'Wajib diisi',
        'max' => [
            'string' => 'The :attribute may not be greater than :max characters.',
        ],
        'custom' => [
            'email' => [
                'required' => 'We need to know your email address!',
            ],
            'name' => [
                'required' => 'Name is required.',
                'max' => 'Name cannot be more than :max characters.',
            ],
        ],
        'attributes' => [
            'email' => 'Email Address',
            'name' => 'Full Name',
        ],
    ];
