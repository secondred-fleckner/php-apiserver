<?php    
    
    namespace lib;
    
    class dbQ
    {
        private $arrSelect;
        private $arrFrom;
        private $arrJoints;
        private $arrWhere;
        private $strOrder;
        private $strGroup;
        private $strLimit;

        private $resFetch = null;

        public function __construct() {
            $this->arrFrom = array();
            $this->arrJoints = array();
            $this->arrSelect = array();
            $this->arrWhere = array();
        }

        public function query() {
            return 'SELECT '.trim(implode(',',$this->arrSelect), ',').'
                    FROM '.trim(implode(',',$this->arrFrom), ',').' '.implode(' ',$this->arrJoints).'
                    '.((count($this->arrWhere) > 0) ? ' WHERE 1=1 AND ('.implode(') AND (', $this->arrWhere).')' : '').' '.$this->strGroup.' '.$this->strOrder.' '.$this->strLimit.';';
        }

        /**
         * dbQ::merge()
         * Erweitert das dbQ Objekt um Einschraenkungen und Joins aus dem uebergebenen
         * @param \lib\dbQ $dbQ \lib\dbQ Objekt
         * @return \lib\dbQ self
         */
        public function merge($dbQ)
        {
            $arrRaw = $dbQ->getRaw();

            if (is_array($arrRaw['select']))
                $this->arrSelect = array_merge($this->arrSelect, $arrRaw['select']);

            if (is_array($arrRaw['from']))
                $this->arrFrom = array_merge($this->arrFrom, $arrRaw['from']);

            if (is_array($arrRaw['joints']))
                $this->arrJoints = array_merge($this->arrJoints, $arrRaw['joints']);

            if (is_array($arrRaw['where']))
                $this->arrWhere = array_merge($this->arrWhere, $arrRaw['where']);

            if ($arrRaw['order'])
                $this->strOrder = $arrRaw['order'];

            if ($arrRaw['group'])
                $this->strGroup = $arrRaw['group'];

            if ($arrRaw['limit'])
                $this->strLimit = $arrRaw['limit'];
        }

        public function getRaw()
        {
            return array(   'select' => $this->arrSelect,
                            'from' => $this->arrFrom,
                            'joints' => $this->arrJoints,
                            'where' => $this->arrWhere,
                            'order' => $this->strOrder,
                            'group' => $this->strGroup,
                            'limit' => $this->strLimit      );
        }

        public function fetch($bRefetch = false)
        {
            global $DB;
            if ($this->resFetch == null || $bRefetch == true)
            {
                $this->resFetch = $DB->query($this->__toString());
            }
            return mysqli_fetch_assoc($this->resFetch);
        }

        public function select($strSelect) {
            array_push($this->arrSelect, $strSelect);
            return $this;
        }
        public function from($strFrom) {
            array_push($this->arrFrom, $strFrom);
            return $this;
        }
        public function join($strJoin, $bLeftJoin=false) {
            array_push($this->arrJoints, (($bLeftJoin) ? 'LEFT JOIN '.$strJoin : 'JOIN '.$strJoin));
            return $this;
        }
        public function add($strAnd) {
            array_push($this->arrWhere, $strAnd);
            return $this;
        }
        public function order($strOrder) {
            $this->strOrder = 'ORDER BY '.$strOrder;
            return $this;
        }
        public function group($strOrder) {
            $this->strGroup = 'GROUP BY '.$strOrder;
            return $this;
        }
        public function limit($intStart,$intAmount=0) {
            if ($intStart && !is_numeric($intStart))
            {
                $this->strLimit = 'LIMIT '.$intStart;
                return $this;
            }

            if (!$intAmount)
                $this->strLimit = 'LIMIT '.$intStart;
            else
                $this->strLimit = 'LIMIT '.$intStart.','.$intAmount;
            return $this;
        }
        public function __toString() {
            #echo $this->query();
            return "".$this->query()."";
        }
    }
