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
     * @var array Text link for previous and next page
     */
    private $pages = [
        "fr" => ["Précédent", "Suivant"],
        "en" => ["Previous", "Next"]
    ];

    /**
     * @var string Default lang
     */
    private $lang = "en";

    /**
     * @var array Data
     */
    private $data;

    /**
     * @param array $data Data to sort (optionnal)
     * @param int $byPage Number limit for pagination (optionnal)
     * @param int $page Current number page for pagination (optionnal)
     */
    public function __construct(array $data = [], int $byPage = 20, int $page = 1, string $lang = "en")
    {
        $this->data = $data;

        if (is_int($byPage) && $byPage >= 1) {
            $this->byPage = $byPage;
        } else {
            throw new \Exception('Unable build: Argument $byPage must be an integer and greater than 0');
        }

        if (is_int(intval($page)) && intval($page) >= 1) {
            $this->page = $page;
        } else {
            throw new \Exception('Unable build: Argument $page must be an integer and greater than 0');
        }

        if (is_string($lang) && array_key_exists($lang, $this->pages)) {
            $this->lang = $lang;
        } else {
            throw new \Exception('Unable build: Argument $lang must be an string and exist in languages supported');
        }
    }

    /**
     * @param array $pagination params for construct pagination
     * @return string Return html pagination
     */
    private function generatePagination(array $pagination)
    {
        $html = "";
        if (isset($pagination["cssClass"]) && is_array($pagination["cssClass"])) {
            $class = implode(" ", $pagination["cssClass"]);
        } else {
            $class = "";
        }

        if ($this->byPage != null && $this->byPage < count($this->data)) {
            $html .= "<ul class=\"".$class."\">";

            if ($this->url != "#" && preg_match("#{}#", $this->url) == 1) {
                $urlPrevious = str_replace("{}", $this->page-1, $this->url);
                $urlNext = str_replace("{}", $this->page+1, $this->url);
            } else {
                $urlPrevious = "#";
                $urlNext = "#";
            }

            if ($this->byPage > 1) {
                if (isset($pagination["lang"]) && isset($this->pages[$pagination["lang"]])) {
                    $previous = $this->pages[$pagination["lang"]][0];
                    $next = $this->pages[$pagination["lang"]][1];
                } else {
                    $previous = $this->pages["en"][0];
                    $next = $this->pages["en"][1];
                }

                if ($this->page > 1) {
                    $html .= "<li><a href=\"".$urlPrevious."\">".$previous."</a></li>";
                }
            }

            if ($this->page < (count($this->data)/$this->byPage) && isset($next)) {
                $html .= "<li><a href=\"".$urlNext."\">".$next."</a></li>";
            }
            $html .= "</ul>";
        }
        return $html;
    }

    /**
     * @param string $on Column to order
     * @param string $order Meaning order by 'ASC' or 'DESC' (optionnal)
     * @return bool Return true if data sorted | Return false if data array empty or not a array
     */
    public function dataSort(string $on, string $order = 'ASC')
    {
        if (!empty($this->data)) {
            $new_array = [];
            $sortable_array = [];

            if (count($this->data) > 0) {
                foreach ($this->data as $k => $v) {
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
     * @param array $columns
     * @return bool Return true if data column(s) filtered | Return false if not filtered
     */
    public function dataColumnFilter(string $action = "skip", array $columns = [])
    {
        if (!empty($this->data)) {
            if ($action === "skip" || $action === "keep") {
                if (is_array($columns)) {
                    if (count($this->data) > 0) {
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
                                throw new \Exception('Unable to filter: Bad array format');
                            }
                        }
                    }

                    $this->data = array_merge([], $this->data);
                    return true;
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
     * @param array $columns
     * @return bool Return true if data filtered | Return false if not filtered
     */
    public function dataFilter(array $columns = [])
    {
        if (!empty($this->data)) {
            if (is_array($columns)) {
                if (count($this->data) > 0) {
                    foreach ($this->data as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $k2 => $v2) {
                                if (isset($columns[$k2]) && $v2 != $columns[$k2]) {
                                    unset($this->data[$k]);
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
                throw new \Exception('Unable to filter: Bad format columns param');
            }
        } else {
            return false;
        }
    }

    /**
     * @param array $cssClass Array css class for table balise (optionnal)
     * @return string Return table html code with css class or not and data by page limit
     */
    public function generateTable(array $cssClass = [], array $pagination = [])
    {
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

        if ($this->byPage == null) {
            $count = count($this->data);
            $page = 0;
        } else {
            $count = $this->byPage;
            $page = $this->page-1;
        }

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
                $html .= "<td>".$v."</td>";
            }
            $html .= "</tr>";
        }

        $html .= "</tbody>
            </table>";

        // Pagination BOTTOM or FULL
        if (isset($pagination["position"])
        && ($pagination["position"] == "bottom" || $pagination["position"] == "full")) {
            $html .= $this->generatePagination($pagination);
        }

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
            throw new \Exception('Unable to "setByPage": Argument must be an integer and greater than 0');
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
            throw new \Exception('Unable to "setPage": Argument must be an integer and greater than 0');
        }
    }

    /**
     * @return string Url pagination
     */
    public function getUrl()
    {
        return str_replace("{}", $this->page, $this->url);
    }

    /**
     * @return string Default language
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang Default language
     * @return string lang Default language
     */
    public function setLang(string $lang)
    {
        if (is_string($lang) && array_key_exists($lang, $this->pages)) {
            $this->lang = $lang;
            return $this->lang;
        } else {
            throw new \Exception('Unable build: Argument must be an string and exist in languages supported');
        }
    }
}
