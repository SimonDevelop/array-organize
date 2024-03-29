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
 * Class ArrayOrganize
 * Sort data for pagination, filtre sort and more.
 */
class ArrayOrganize
{
    /**
     * @var int Number Data by page limit
     */
    private $byPage;

    /**
     * @var int Current number page
     */
    private $page;

    /**
     * @var string Url pagination
     */
    private $url = "#";

    /**
     * @var array Data
     */
    private $data;

    /**
     * @var array Functions columns
     */
    private $functions = [];

    /**
     * @var array Totals columns
     */
    private $totals = [];

    /**
     * @param array $data Data to sort (optionnal)
     * @param int $byPage Number limit for pagination (optionnal)
     * @param int $page Current number page for pagination (optionnal)
     */
    public function __construct(array $data = [], int $byPage = 20, int $page = 1)
    {
        $this->data = $data;

        if ($byPage >= 1) {
            $this->byPage = $byPage;
        } else {
            throw new \InvalidArgumentException('Unable build: Argument $byPage must be an integer and greater than 0');
        }

        if ($page >= 1) {
            $this->page = $page;
        } else {
            throw new \InvalidArgumentException('Unable build: Argument $page must be an integer and greater than 0');
        }
    }

    /**
     * @param array $pagination params for construct pagination
     * @return string Return html pagination
     */
    public function generatePagination(array $pagination)
    {
        $html = "";
        if (isset($pagination["cssClass"]) && is_array($pagination["cssClass"])) {
            if (isset($pagination["cssClass"]["ul"]) && is_string($pagination["cssClass"]["ul"])) {
                $classUl = trim($pagination["cssClass"]["ul"]);
            } else {
                $classUl = "";
            }
            if (isset($pagination["cssClass"]["li"]) && is_string($pagination["cssClass"]["li"])) {
                $classLi = trim($pagination["cssClass"]["li"]);
            } else {
                $classLi = "";
            }
            if (isset($pagination["cssClass"]["a"]) && is_string($pagination["cssClass"]["a"])) {
                $classA = trim($pagination["cssClass"]["a"]);
            } else {
                $classA = "";
            }
            if (isset($pagination["cssClass"]["disabled"]) && is_array($pagination["cssClass"]["disabled"])) {
                foreach ($pagination["cssClass"]["disabled"] as $k => $v) {
                    if ($k == "li") {
                        $classDisabledLi = $v;
                    }
                    if ($k == "a") {
                        $classDisabledA = $v;
                    }
                }
                if (!isset($classDisabledLi)) {
                    $classDisabledLi = "";
                }
                if (!isset($classDisabledA)) {
                    $classDisabledA = "";
                }
            } else {
                $classDisabledLi = "";
                $classDisabledA = "";
            }
            if (isset($pagination["cssClass"]["active"]) && is_array($pagination["cssClass"]["active"])) {
                foreach ($pagination["cssClass"]["active"] as $k => $v) {
                    if ($k == "li") {
                        $classActiveLi = $v;
                    }
                    if ($k == "a") {
                        $classActiveA = $v;
                    }
                }
                if (!isset($classActiveLi)) {
                    $classActiveLi = "";
                }
                if (!isset($classActiveA)) {
                    $classActiveA = "";
                }
            } else {
                $classActiveLi = "";
                $classActiveA = "";
            }
        } else {
            $classUl = "";
            $classLi = "";
            $classA = "";

            $classDisabledLi = "";
            $classDisabledA = "";

            $classActiveLi = "";
            $classActiveA = "";
        }

        if ($this->byPage != null && $this->byPage < count($this->data)) {
            $html .= "<ul class=\"".$classUl."\">";

            if (isset($pagination["url"]) && preg_match("#{}#", $pagination["url"]) == 1) {
                $urlPrevious = str_replace("{}", strval($this->page-1), $pagination["url"]);
                $urlNext = str_replace("{}", strval($this->page+1), $pagination["url"]);
            } else {
                if ($this->url != "#" && preg_match("#{}#", $this->url) == 1) {
                    $urlPrevious = str_replace("{}", strval($this->page-1), $this->url);
                    $urlNext = str_replace("{}", strval($this->page+1), $this->url);
                } else {
                    $urlPrevious = "#";
                    $urlNext = "#";
                }
            }

            if (isset($pagination["lang"]["previous"]) && isset($pagination["lang"]["next"])) {
                $previous = $pagination["lang"]["previous"];
                $next = $pagination["lang"]["next"];
            } else {
                $previous = "Previous";
                $next = "Next";
            }

            if ($this->page > 1) {
                $html .= "<li class=\"".$classLi."\">
                            <a class=\"".$classA."\" href=\"".$urlPrevious."\">".$previous."</a>
                          </li>";
            } else {
                $html .= "<li class=\"".trim($classLi." ".$classDisabledLi)."\">
                            <a class=\"".trim($classA." ".$classDisabledA)."\">".$previous."</a>
                          </li>";
            }

            $pages = count($this->data)/$this->byPage;
            if (is_float($pages)) {
                $pages = intval($pages)+1;
            }
            for ($i=1; $i <= $pages; $i++) {
                if ($i == $this->page) {
                    $html .= "<li class=\"".trim($classLi." ".$classActiveLi)."\">
                                <a class=\"".trim($classA." ".$classActiveA)."\">".$i."</a>
                              </li>";
                } else {
                    $html .= "<li class=\"".$classLi."\">
                                <a class=\"".$classA."\" href=\""
                                    .str_replace("{}", strval($i), $this->url)."\">".$i.
                                "</a>
                              </li>";
                }
            }

            if ($this->page < (count($this->data)/$this->byPage)) {
                $html .= "<li class=\"".$classLi."\">
                            <a class=\"".$classA."\" href=\"".$urlNext."\">".$next."</a>
                          </li>";
            } else {
                $html .= "<li class=\"".trim($classLi." ".$classDisabledLi)."\">
                            <a class=\"".trim($classA." ".$classDisabledA)."\">".$next."</a>
                          </li>";
            }
            $html .= "</ul>";
        }
        return $html;
    }

    /**
     * @param string $on Column to order data
     * @param string $order Meaning order by 'ASC' or 'DESC' (optionnal)
     * @return bool Return true if data sorted | Return false if data array empty or not a array
     */
    public function dataSort(string $on, string $order = 'ASC')
    {
        if (!empty($this->data)) {
            $new_array = [];
            $sortable_array = [];

            if (count($this->data) != 0) {
                foreach ($this->data as $k => $v) {
                    if (is_array($v)) {
                        if (isset($v[$on])) {
                            foreach ($v as $k2 => $v2) {
                                if ($k2 == $on) {
                                    $sortable_array[$k] = $v2;
                                }
                            }
                        } else {
                            throw new \InvalidArgumentException('Unable to sort: No "'.$on.'" columns found');
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
                    $new_array[$k] = $this->data[$k];
                }
            }

            $this->data = array_merge([], $new_array);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $action filter "skip" or "keep" columns list
     * @param array $columns list
     * @return bool Return true if data column(s) filtered | Return false if not filtered
     */
    public function dataColumnFilter(string $action = "skip", array $columns = [])
    {
        if (!empty($this->data)) {
            if ($action === "skip" || $action === "keep") {
                if (count($this->data) != 0) {
                    foreach ($this->data as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $k2 => $v2) {
                                if (in_array($k2, $columns)) {
                                    if ($action == "skip") {
                                        unset($this->data[$k][$k2]);
                                    }
                                } else {
                                    if ($action == "keep") {
                                        unset($this->data[$k][$k2]);
                                    }
                                }
                            }
                        } else {
                            throw new \InvalidArgumentException('Unable to filter: Bad array format');
                        }
                    }
                }

                $this->data = array_merge([], $this->data);
                return true;
            } else {
                throw new \InvalidArgumentException('Unable to filter: Bad action param');
            }
        } else {
            return false;
        }
    }

    /**
     * @param array $columns filter param
     * @return bool Return true if data filtered | Return false if not filtered
     */
    public function dataFilter(array $columns = [])
    {
        if (!empty($this->data)) {
            if (count($this->data) != 0) {
                foreach ($this->data as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            foreach ($columns as $key => $val) {
                                if (!is_array($val)) {
                                    if (substr($val, -1) == "%" && substr($val, 0, 1) == "%") {
                                        $explode = explode("%", $val);
                                        if (isset($columns[$k2]) && !preg_match("#".$explode[1]."#", $v2)) {
                                            unset($this->data[$k]);
                                        }
                                    } elseif (substr($key, -3) === "[<]") {
                                        $explode = explode("[<]", $key);
                                        $kv = $explode[0];
                                        if (preg_match(
                                            "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
                                            $val
                                        )) {
                                            if (isset($this->data[$k])) {
                                                $val = new \DateTime($val);
                                                $val2 = new \DateTime($this->data[$k][$kv]);
                                                if ($val2 < $val || $val2 == $val) {
                                                    unset($this->data[$k]);
                                                }
                                            }
                                        } else {
                                            if (isset($this->data[$k])
                                            && floatval($this->data[$k][$kv]) >= floatval($val)) {
                                                unset($this->data[$k]);
                                            }
                                        }
                                    } elseif (substr($key, -4) === "[<=]") {
                                        $explode = explode("[<=]", $key);
                                        $kv = $explode[0];
                                        if (preg_match(
                                            "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
                                            $val
                                        )) {
                                            if (isset($this->data[$k])) {
                                                $val = new \DateTime($val);
                                                $val2 = new \DateTime($this->data[$k][$kv]);
                                                if ($val2 < $val) {
                                                    unset($this->data[$k]);
                                                }
                                            }
                                        } else {
                                            if (isset($this->data[$k])
                                            && floatval($this->data[$k][$kv]) > floatval($val)) {
                                                unset($this->data[$k]);
                                            }
                                        }
                                    } elseif (substr($key, -3) === "[>]") {
                                        $explode = explode("[>]", $key);
                                        $kv = $explode[0];
                                        if (preg_match(
                                            "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
                                            $val
                                        )) {
                                            if (isset($this->data[$k])) {
                                                $val = new \DateTime($val);
                                                $val2 = new \DateTime($this->data[$k][$kv]);
                                                if ($val2 > $val || $val2 == $val) {
                                                    unset($this->data[$k]);
                                                }
                                            }
                                        } else {
                                            if (isset($this->data[$k])
                                            && floatval($this->data[$k][$kv]) <= floatval($val)) {
                                                unset($this->data[$k]);
                                            }
                                        }
                                    } elseif (substr($key, -4) === "[>=]") {
                                        $explode = explode("[>=]", $key);
                                        $kv = $explode[0];
                                        if (preg_match(
                                            "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",
                                            $val
                                        )) {
                                            if (isset($this->data[$k])) {
                                                $val = new \DateTime($val);
                                                $val2 = new \DateTime($this->data[$k][$kv]);
                                                if ($val2 > $val) {
                                                    unset($this->data[$k]);
                                                }
                                            }
                                        } else {
                                            if (isset($this->data[$k])
                                            && floatval($this->data[$k][$kv]) < floatval($val)) {
                                                unset($this->data[$k]);
                                            }
                                        }
                                    } elseif (substr($key, -4) === "[!=]") {
                                        $explode = explode("[!=]", $key);
                                        $kv = $explode[0];
                                        if (isset($this->data[$k][$kv]) && $this->data[$k][$kv] == $columns[$key]) {
                                            unset($this->data[$k]);
                                        }
                                    } else {
                                        if (isset($columns[$k2]) && $v2 != $columns[$k2]) {
                                            unset($this->data[$k]);
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

            $this->data = array_merge([], $this->data);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $column Name of the column for used function
     * @param string $function Name of function for column
     * @param array $params Parameters of function
     * @return bool returns true or false if error
     */
    public function addFunction(string $column, string $function, array $params = [])
    {
        foreach ($this->data as $k => $v) {
            if (is_array($v)) {
                if (!array_key_exists($column, $v)) {
                    return false;
                }
            } else {
                throw new \InvalidArgumentException('Unable to filter: Bad format data for (addFunction)');
            }
        }
        $this->functions[$column] = [
            "function" => $function,
            "params" => $params
        ];
        return true;
    }

    /**
     * @param string $column Name of the column for used function
     * @param string $text Text or html before total
     * @return bool returns true or false if error
     */
    public function addTotal(string $column, string $text = "")
    {
        foreach ($this->data as $k => $v) {
            if (is_array($v)) {
                if (!array_key_exists($column, $v)) {
                    return false;
                } else {
                    if (!is_int($v[$column]) && !is_float($v[$column])) {
                        return false;
                    }
                }
            } else {
                throw new \InvalidArgumentException('Unable to filter: Bad format data for (addTotal)');
            }
        }
        $this->totals[$column] = [
            "text" => $text,
            "value" => 0
        ];
        return true;
    }

    /**
     * @param array $cssClass Array css class for table balise (optionnal)
     * @param array $pagination Array pagination settings (optionnal)
     * @return string|false Return table html code with css class or not and data by page limit | Return false otherwise
     */
    public function generateTable(array $cssClass = [], array $pagination = [])
    {
        if (!empty($this->data)) {
            foreach ($this->data as $v) {
                foreach ($v as $val) {
                    if (is_array($val)) {
                        return false;
                    }
                }
            }

            if (!empty($cssClass)) {
                $class_table = implode(" ", $cssClass);
            } else {
                $class_table = "";
            }

            if (isset($pagination["url"])) {
                $this->url = $pagination["url"];
            }

            $column = [];
            $line = [];

            foreach ($this->data as $array) {
                $column = array_keys($array);
                break;
            }

            $count = $this->byPage;
            $page = $this->page-1;

            $nb = 0;
            for ($i = (0+($page*$count)); $i < ($count+($page*$count)); $i++) {
                if (isset($this->data[$i])) {
                    foreach ($this->data[$i] as $k => $v) {
                        $line[$nb][] = $v;
                    }
                    $nb++;
                }
            }
            $html = "";

            // Pagination TOP or FULL
            if (isset($pagination["position"])
            && ($pagination["position"] == "top" || $pagination["position"] == "full")) {
                $html .= $this->generatePagination($pagination);
            }

            $html .= "<table class=\"".$class_table."\">
                  <thead>";
            $html .= "<tr>";

            for ($i = 0; $i < count($column); $i++) {
                $html .= "<th>".$column[$i]."</th>";
            }

            $html .= "</tr>
                </thead>";
            $html .= "<tbody>";

            foreach ($line as $array) {
                $html .= "<tr>";
                foreach ($array as $k => $v) {
                    if (!empty($this->functions) && array_key_exists($column[$k], $this->functions)) {
                        $function = $this->functions[$column[$k]]["function"];
                        $params = array_merge([$v], $this->functions[$column[$k]]["params"]);
                        $html .= "<td>".call_user_func_array($function, $params)."</td>";
                    } else {
                        $html .= "<td>".$v."</td>";
                    }

                    if (!empty($this->totals) && array_key_exists($column[$k], $this->totals)
                    && (is_int($v) || is_float($v))) {
                        $this->totals[$column[$k]]['value'] += $v;
                    }
                }
                $html .= "</tr>";
            }

            if (!empty($this->functions)) {
                $this->functions = [];
            }

            if (!empty($this->totals)) {
                $html .= "<tr>";
                foreach ($column as $k => $v) {
                    if (array_key_exists($v, $this->totals)) {
                        $html .= "<td>".$this->totals[$v]["text"].$this->totals[$v]['value']."</td>";
                    } else {
                        $html .= "<td></td>";
                    }
                }
                $html .= "</tr>";
                $this->totals = [];
            }

            $html .= "</tbody>
                </table>";

            // Pagination BOTTOM or FULL
            if (isset($pagination["position"])
            && ($pagination["position"] == "bottom" || $pagination["position"] == "full")) {
                $html .= $this->generatePagination($pagination);
            }

            return $html;
        } else {
            return false;
        }
    }

    /**
     * @param array $cssClass Array css class for list balise (optionnal)
     * @return string|false Return list html code with css class or not | return false if data empty or bad format
     */
    public function generateList(array $cssClass = [])
    {
        $html = "";
        if (!empty($cssClass)) {
            if (isset($cssClass["ul"]) && is_string($cssClass["ul"])) {
                $classUl = trim($cssClass["ul"]);
            } else {
                $classUl = "";
            }
            if (isset($cssClass["li"]) && is_string($cssClass["li"])) {
                $classLi = trim($cssClass["li"]);
            } else {
                $classLi = "";
            }
            if (isset($cssClass["a"]) && is_string($cssClass["a"])) {
                $classA = trim($cssClass["a"]);
            } else {
                $classA = "";
            }
            if (isset($cssClass["disabled"]) && is_array($cssClass["disabled"])) {
                foreach ($cssClass["disabled"] as $k => $v) {
                    if ($k == "li") {
                        $classDisabledLi = $v;
                    }
                    if ($k == "a") {
                        $classDisabledA = $v;
                    }
                }
                if (!isset($classDisabledLi)) {
                    $classDisabledLi = "";
                }
                if (!isset($classDisabledA)) {
                    $classDisabledA = "";
                }
            } else {
                $classDisabledLi = "";
                $classDisabledA = "";
            }
            if (isset($cssClass["active"]) && is_array($cssClass["active"])) {
                foreach ($cssClass["active"] as $k => $v) {
                    if ($k == "li") {
                        $classActiveLi = $v;
                    }
                    if ($k == "a") {
                        $classActiveA = $v;
                    }
                }
                if (!isset($classActiveLi)) {
                    $classActiveLi = "";
                }
                if (!isset($classActiveA)) {
                    $classActiveA = "";
                }
            } else {
                $classActiveLi = "";
                $classActiveA = "";
            }
            if (isset($cssClass["balise"]) && is_string($cssClass["balise"])) {
                $balise = [];
                if (preg_match("#/#", $cssClass["balise"])) {
                    $explode = explode("/", $cssClass["balise"]);
                    if ($explode[0] != $explode[1] && !empty($explode[0]) && !empty($explode[1])) {
                        if ($explode[0] == "a" && $explode[1] == "li") {
                            $balise = ["a", "li"];
                        }
                        if ($explode[0] == "li" && $explode[1] == "a") {
                            $balise = ["li", "a"];
                        }
                    } else {
                        $balise = ["li", "a"];
                    }
                } elseif ($cssClass["balise"] == "li") {
                    $balise = ["li"];
                } elseif ($cssClass["balise"] == "a") {
                    $balise = ["a"];
                } else {
                    $balise = ["li", "a"];
                }
            } else {
                $balise = ["li", "a"];
            }
        } else {
            $classUl = "";
            $classLi = "";
            $classA = "";

            $classDisabledLi = "";
            $classDisabledA = "";

            $classActiveLi = "";
            $classActiveA = "";
        }

        $html .= "<ul class=\"".$classUl."\">";

        foreach ($this->data as $k => $v) {
            $classForThisLi = $classLi;
            $classForThisA = $classA;
            if (isset($v["title"]) && is_string($v["title"])) {
                if (isset($v["active"]) && $v["active"] == true) {
                    $classForThisLi .= " ".$classActiveLi;
                    $classForThisA .= " ".$classActiveA;
                }

                if (isset($v["disabled"]) && $v["disabled"] == true) {
                    $classForThisLi .= " ".$classDisabledLi;
                    $classForThisA .= " ".$classDisabledA;
                }

                if (isset($v["url"]) && !empty($v["url"]) && is_string($v["url"])) {
                    $htmlHref = " href=\"".$v["url"]."\"";
                } else {
                    $htmlHref = "";
                }

                if (isset($v["target_blank"]) && $v["target_blank"] == true) {
                    $htmlTarget = " target=\"_blank\"";
                } else {
                    $htmlTarget = "";
                }

                if (isset($balise[0]) && isset($balise[1])) {
                    if ($balise[0] == "li" && $balise[1] == "a") {
                        $html .= "<li class=\"".$classForThisLi."\">";
                        $html .= "<a class=\"".$classForThisA."\"".$htmlHref.$htmlTarget.">".$v["title"]."</a>";
                        $html .= "</li>";
                    } elseif ($balise[0] == "a" && $balise[1] == "li") {
                        $html .= "<a class=\"".$classForThisA."\"".$htmlHref.$htmlTarget.">";
                        $html .= "<li class=\"".$classForThisLi."\">".$v["title"]."</li>";
                        $html .= "</a>";
                    }
                }

                if (isset($balise[0]) && !isset($balise[1]) && $balise[0] == "li") {
                    $html .= "<li class=\"".$classForThisLi."\">".$v["title"]."</li>";
                }

                if (isset($balise[0]) && !isset($balise[1]) && $balise[0] == "a") {
                    $html .= "<a class=\"".$classForThisA."\" ".$htmlHref.$htmlTarget.">".$v["title"]."</a>";
                }
            } else {
                return false;
            }
        }

        $html .= "</ul>";

        return $html;
    }

    /**
     * @return array Data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return array data
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this->data;
    }

    /**
     * @return int Number data by page
     */
    public function getByPage()
    {
        return $this->byPage;
    }

    /**
     * @param int $byPage Number data by page
     * @return int byPage Number data by page
     */
    public function setByPage(int $byPage)
    {
        if (is_int($byPage) && $byPage >= 1) {
            $this->byPage = $byPage;
            return $this->byPage;
        } else {
            throw new \InvalidArgumentException(
                'Unable to "setByPage": Argument must be an integer and greater than 0'
            );
        }
    }

    /**
     * @return int page Current number page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page Current number page
     * @return int page Current number page
     */
    public function setPage(int $page)
    {
        if (is_int($page) && $page >= 1) {
            $this->page = $page;
            return $this->page;
        } else {
            throw new \InvalidArgumentException('Unable to "setPage": Argument must be an integer and greater than 0');
        }
    }

    /**
     * @return string Current url
     */
    public function getUrl()
    {
        return str_replace("{}", strval($this->page), $this->url);
    }

    /**
     * @param string $url Url with pattern for page id
     * @return string Current url
     */
    public function setUrl(string $url)
    {
        if (preg_match("#{}#", $url)) {
            $this->url = $url;
            return str_replace("{}", strval($this->page), $this->url);
        } else {
            throw new \InvalidArgumentException(
                'Unable to "setUrl": Argument must be an string and contain pattern "{}" for id page'
            );
        }
    }
}
