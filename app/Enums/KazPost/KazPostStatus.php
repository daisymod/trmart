<?php

namespace App\Enums\KazPost;


class KazPostStatus
{
    public const ON_WAY = [
        "Registered",
        "Departed",
        "Transit",
        "Arrived",
        "DLV", 
        "DLVPAY", 
        "PRC",
        "RCPOPS",
        "SND",
    ];

    public const RETURN = [
        "RET", 
        "Returned",
        "ReturnedToCompany",
        "RETCC",
        "RETSC",
    ];

    public const ON_EXPECTED = [
        "Delivered",
        "DLV_POBOX",
        "DLV_I",
        "DLV_O",
        "EDH",
        "STR",
        "STR_EMS",
    ];

    public const DELIVERED = [
        "DPAY",
        "ISSPAY",
        "ISSSC",
        "Handed",
        "BOXISS",
        "BOXISS_I",
        "BOXISS_O",
        "DLV_POBOX_I",
        "DLV_POBOX_O",
        "DPAY_I",
        "DPAY_O",
        "ISSPAY_I",
        "ISSPAY_O",
        "ISSSC_I",
        "ISSSC_O",
        "EMI",
        "DLV_HAND",
        "DLV",
    ];
}
