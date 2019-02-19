<?php

/*
 * This file is the array-organize package.
 *
 * (c) Simon Micheneau <contact@simon-micheneau.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SimonDevelop;

/**
 * Class ArrayOrganizeFacade
 * Sort data for pagination, filtre sort and more.
 */
class ArrayOrganizeFacade
{
    /**
     * @param array $data data array
     * @param string $on Column to order data
     * @param string $order Meaning order by 'ASC' or 'DESC' (optionnal)
     * @return bool Return true if data sorted | Return false if data array empty or not a array
     */
    public static function dataSort(array $data, string $on, string $order = 'ASC')
    {
        if (!empty($data)) {
            $new_array = [];
            $sortable_array = [];

            if (count($data) > 0) {
                foreach ($data as $k => $v) {
                    if (is_array($v)) {
                        if (isset($v[$on])) {
                            foreach ($v as $k2 => $v2) {
                                if ($k2 == $on) {
                                    $sortable_array[$k] = $v2;
                                }
                            }
                        } else {
                            throw new \Exception('Unable to sort: No "'.$on.'" columns found');
                        }
                    } else {
                        throw new \Exception('Unable to sort: Bad array format');
                    }
                }

                switch ($order) {
                    case 'ASC':
                        asort($sortable_array);
                        break;
                    case 'DESC':
                        arsort($sortable_array);
                        break;
                }

                foreach ($sortable_array as $k => $v) {
                    $new_array[$k] = $data[$k];
                }
            }

            $data = array_merge([], $new_array);
            return $data;
        } else {
            return false;
        }
    }

    /**
     * @param array $data data array
     * @param string $action filter "skip" or "keep" columns list
     * @param array $columns list
     * @return bool Return true if data column(s) filtered | Return false if not filtered
     */
    public static function dataColumnFilter(array $data, string $action = "skip", array $columns = [])
    {
        if (!empty($data)) {
            if ($action === "skip" || $action === "keep") {
                if (is_array($columns)) {
                    if (count($data) > 0) {
                        foreach ($data as $k => $v) {
                            if (is_array($v)) {
                                foreach ($v as $k2 => $v2) {
                                    if (in_array($k2, $columns)) {
                                        if ($action == "skip") {
                                            unset($data[$k][$k2]);
                                        }
                                    } else {
                                        if ($action == "keep") {
                                            unset($data[$k][$k2]);
                                        }
                                    }
                                }
                            } else {
                                throw new \Exception('Unable to filter: Bad array format');
                            }
                        }
                    }

                    $data = array_merge([], $data);
                    return $data;
                } else {
                    throw new \Exception('Unable to filter: Bad format columns param');
                }
            } else {
                throw new \Exception('Unable to filter: Bad action param');
            }
        } else {
            return false;
        }
    }

    /**
     * @param array $data data array
     * @param array $columns filter param
     * @return bool Return true if data filtered | Return false if not filtered
     */
    public static function dataFilter(array $data, array $columns = [])
    {
        if (!empty($data)) {
            if (is_array($columns)) {
                if (count($data) > 0) {
                    foreach ($data as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $k2 => $v2) {
                                foreach ($columns as $key => $val) {
                                    if (!is_array($val)) {
                                        if (substr($val, -1) == "%" && substr($val, 0, 1) == "%") {
                                            $explode = explode("%", $val);
                                            if (isset($columns[$k2]) && !preg_match("#".$explode[1]."#", $v2)) {
                                                unset($data[$k]);
                                            }
                                        } elseif (substr($key, -3) === "[<]") {
                                            $explode = explode("[<]", $key);
                                            $kv = $explode[0];
                                            if (preg_match(
                                                "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
                                                $val
                                            )) {
                                                $val = new \DateTime($val);
                                                $val2 = new \DateTime($data[$k][$kv]);
                                                if (isset($data[$k]) && $val2 >= $val) {
                                                    unset($data[$k]);
                                                }
                                            } else {
                                                if (isset($data[$k])
                                                && floatval($data[$k][$kv]) >= floatval($val)) {
                                                    unset($data[$k]);
                                                }
                                            }
                                        } elseif (substr($key, -4) === "[<=]") {
                                            $explode = explode("[<=]", $key);
                                            $kv = $explode[0];
                                            if (preg_match(
                                                "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
                                                $val
                                            )) {
                                                $val = new \DateTime($val);
                                                $val2 = new \DateTime($data[$k][$kv]);
                                                if (isset($data[$k]) && $val2 > $val) {
                                                    unset($data[$k]);
                                                }
                                            } else {
                                                if (isset($data[$k])
                                                && floatval($data[$k][$kv]) > floatval($val)) {
                                                    unset($data[$k]);
                                                }
                                            }
                                        } elseif (substr($key, -3) === "[>]") {
                                            $explode = explode("[>]", $key);
                                            $kv = $explode[0];
                                            if (preg_match(
                                                "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
                                                $val
                                            )) {
                                                $val = new \DateTime($val);
                                                $val2 = new \DateTime($data[$k][$kv]);
                                                if (isset($data[$k]) && $val2 <= $val) {
                                                    unset($data[$k]);
                                                }
                                            } else {
                                                if (isset($data[$k])
                                                && floatval($data[$k][$kv]) <= floatval($val)) {
                                                    unset($data[$k]);
                                                }
                                            }
                                        } elseif (substr($key, -4) === "[>=]") {
                                            $explode = explode("[>=]", $key);
                                            $kv = $explode[0];
                                            if (preg_match(
                                                "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
                                                $val
                                            )) {
                                                $val = new \DateTime($val);
                                                $val2 = new \DateTime($data[$k][$kv]);
                                                if (isset($data[$k]) && $val2 < $val) {
                                                    unset($data[$k]);
                                                }
                                            } else {
                                                if (isset($data[$k])
                                                && floatval($data[$k][$kv]) < floatval($val)) {
                                                    unset($data[$k]);
                                                }
                                            }
                                        } elseif (substr($key, -4) === "[!=]") {
                                            $explode = explode("[!=]", $key);
                                            $kv = $explode[0];
                                            if (isset($data[$k][$kv]) && $data[$k][$kv] == $columns[$key]) {
                                                unset($data[$k]);
                                            }
                                        } else {
                                            if (isset($columns[$k2]) && $v2 != $columns[$k2]) {
                                                unset($data[$k]);
                                            }
                                        }
                                    } else {
                                        throw new \Exception('Unable to filter: Bad array format');
                                    }
                                }
                            }
                        } else {
                            throw new \Exception('Unable to filter: Bad array format');
                        }
                    }
                }

                $data = array_merge([], $data);
                return $data;
            } else {
                throw new \Exception('Unable to filter: Bad format columns param');
            }
        } else {
            return false;
        }
    }
}
