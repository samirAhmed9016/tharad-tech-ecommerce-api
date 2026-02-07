<?php

namespace App\Repositories\Setting;


interface SettingRepositoryInterface
{

    /**
     * generalSettingData
     *
     * @return void
     */
    public function generalSettingData();

    /**
     * find the setting
     *
     * @param  mixed $key
     * @return void
     */
    public function find($key);
}
