<?php

namespace Application\Mapper;

use Application\Model\Setting;

class SettingMapper extends BaseMapper
{
    /**
     * @param $data
     * @return Setting
     */
    public function loadSettingObject($data)
    {
        return parent::loadObject($data, 'Application\Model\Setting');
    }

    /**
     * @param $name
     * @return Setting
     */
    public function fetchOneByName($name)
    {
        $data = $this->createQueryBuilder()
            ->select('*')
            ->from('setting', 's')
            ->where('s.name = :name')
            ->setParameter(':name', $name)
            ->execute();

        return $this->loadSettingObject($data);
    }

    public function fetchAllByNames(array $names = array())
    {
        $query = $this->createQueryBuilder();

        $params = array();
        foreach ($names as $name) {
            $params[] = $name;
        }

        $items = $query
            ->select('*')
            ->from('setting', 's')
            ->where($query->expr()->in('s.name', array_fill(0, count($params), '?')))
            ->setParameters($params)
            ->execute()
            ->fetchAll();

        $objects = array();
        foreach ($items as $item) {
            $setting = $this->loadSettingObject($item);
            $objects[$setting->name] = $setting;
        }

        return $objects;
    }

    /**
     * @param $name
     * @param $value
     * @return int
     */
    public function update($name, $value)
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
    public function delete($name)
    {
        return $this->createQueryBuilder()
            ->delete('setting', 's')
            ->where('s.name = :name')
            ->setParameter(':name', $name)
            ->execute();
    }
}
