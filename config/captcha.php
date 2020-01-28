<?php

return [

    'characters' => '0123456789ABCDEFGHJMNPQRTUXYZ',

    'default'   => [
        'length'    => 5,
        'width'     => 120,
        'height'    => 120,
        'quality'   => 90,
    ],

    'flat'   => [
        'length'    => 4,
        'width'     => 162,
        'height'    => 52,
        'quality'   => 90,
        'lines'     => 0,
        'bgImage'   => false,
        'sensitive' => false,
        'bgColor'   => '#ecf2f4',
        'fontColors'=> ['#000000', '#000000', '#000000', '#000000', '#000000', '#000000', '#000000', '#000000'],
        'contrast'  => -5,
        'quality'   => 95,
    ],

    'mini'   => [
        'length'    => 3,
        'width'     => 60,
        'height'    => 32,
    ],

    'inverse'   => [
        'length'    => 5,
        'width'     => 120,
        'height'    => 36,
        'quality'   => 90,
        'sensitive' => true,
        'angle'     => 12,
        'sharpen'   => 10,
        'blur'      => 2,
        'invert'    => true,
        'contrast'  => -5,
    ]

];
