<?php

namespace Application\Mapper;

use Application\Model\Setting;

class UtilityMapper extends BaseMapper
{
    /**
     * @param $data
     * @return \Application\Model\Setting|null
     */
    public function loadSettingObject($data)
    {
        return parent::loadObject($data, 'Application\Model\Setting');
    }

    /**
     * @param $name
     * @return Setting
     */
    public function fetchOneSettingByName($name)
    {
        $query = $this->createQueryBuilder()
            ->select('*')
            ->from('setting', 's')
            ->where('s.name = :name');

        $params = array(
            ':name' => $name
        );

        $data = $this->getConnection()->executeQuery($query, $params)->fetch();
        return $this->loadSettingObject($data);
    }

    /**
     * @param $name
     * @param $value
     * @return int
     */
    public function updateSetting($name, $value)
    {
        $sql = 'REPLACE INTO setting(name, value) VALUES (:name, :value)';
        $params = array(
            ':value' => $value,
            ':name' => $name
        );

        return $this->getConnection()->executeUpdate($sql, $params);
    }

    /**
     * @param $name
     * @return int
     */
    public function deleteSettingByName($name)
    {
        $this->createQueryBuilder()
            ->delete('setting', 's')
            ->where('s.name = :name');

        $params = array(
            'name' => $name
        );

        return $this->getConnection()->executeUpdate('setting', $params);
    }
}
