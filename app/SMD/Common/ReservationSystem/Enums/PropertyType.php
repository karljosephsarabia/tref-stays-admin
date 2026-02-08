<?php

namespace SMD\Common\ReservationSystem\Enums;

class PropertyType
{
    // Short Term types
    const SHORT_TERM_RES_APT_ROOM = 'short_term_res_apt_room';
    const SHORT_TERM_RES_HOU_ROOM = 'short_term_res_hou_room';
    const SHORT_TERM_POOL = 'short_term_pool';
    const SHORT_TERM_PARKING = 'short_term_parking';
    const SHORT_TERM_COM_OFFICE = 'short_term_com_office';
    const SHORT_TERM_COM_WAREHOUSE = 'short_term_com_warehouse';
    const SHORT_TERM_COM_HALL = 'short_term_com_hall';
    
    // Long Term types
    const LONG_TERM_RES_APT = 'long_term_res_apt';
    const LONG_TERM_RES_HOU = 'long_term_res_hou';
    const LONG_TERM_COM_OFFICE = 'long_term_com_office';
    const LONG_TERM_COM_WAREHOUSE = 'long_term_com_warehouse';
    const LONG_TERM_RES_APARTMENT = 'long_term_res_apartment';
    const LONG_TERM_RES_HOUSE = 'long_term_res_house';
    const LONG_TERM_RES_APT_ROOM = 'long_term_res_apt_room';
    const LONG_TERM_RES_HOU_ROOM = 'long_term_res_hou_room';
    const LONG_TERM_PARKING = 'long_term_parking';
    
    // Sale types
    const SALE_RES_APARTMENT = 'sale_res_apartment';
    const SALE_RES_HOUSE = 'sale_res_house';
    const SALE_COM_OFFICE = 'sale_com_office';
    const SALE_COM_WAREHOUSE = 'sale_com_warehouse';
    const SALE_COM_HALL = 'sale_com_hall';
    const SALE_PARKING = 'sale_parking';
    
    // Legacy types
    const HOUSE = 'house';
    const APARTMENT = 'apartment';
    const HOTEL = 'hotel';
    const BEDROOM = 'bedroom';
    const SHORT_TERM_HOUSE = 'short_term_house';
    const SHORT_TERM_APARTMENT = 'short_term_apartment';
    const SHORT_TERM_COMMERCIAL = 'short_term_commercial';
    const LONG_TERM_HOUSE = 'long_term_house';
    const LONG_TERM_APARTMENT = 'long_term_apartment';
    const LONG_TERM_COMMERCIAL = 'long_term_commercial';

    const TYPES = [
        self::HOUSE => 'House',
        self::APARTMENT => 'Apartment',
        self::HOTEL => 'Hotel',
        self::BEDROOM => 'Bedroom',
        self::SHORT_TERM_HOUSE => 'Short Term House',
        self::SHORT_TERM_APARTMENT => 'Short Term Apartment',
        self::LONG_TERM_HOUSE => 'Long Term House',
        self::LONG_TERM_APARTMENT => 'Long Term Apartment',
    ];
}
