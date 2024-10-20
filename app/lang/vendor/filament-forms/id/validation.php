<?php

return [
    'required' => 'Field :attribute wajib diisi.',
    'email' => 'Field :attribute harus berupa alamat email yang valid.',
    'max' => [
        'string' => 'Field :attribute tidak boleh lebih dari :max karakter.',
    ],
    'unique' => 'Field :attribute sudah terdaftar.',
    'distinct' => [
        'must_be_selected' => 'Pilih setidaknya 1 field :attribute.',
        'only_one_must_be_selected' => 'Hanya satu field :attribute yang perlu dipilih.',
    ],

];
