<?php

    namespace lib;

    class Math
    {
        public static function avgMultiplier($arrMultiplier)
        {
            $floatReturn = 1;

            $floatRatioSum = 0;
            $tmp_arrValues = array();

            // Berechne Wichtungs summe um dann anteile verteilen zu koennen
            foreach((array)$arrMultiplier as $mixedValue) {
                if (isset($mixedValue['ratio']))
                    $floatRatioSum += $mixedValue['ratio'];
                else
                    $floatRatioSum++;
            }

            // REchne die Werte Anteilig auf
            foreach((array)$arrMultiplier as $mixedValue)
            {
                $floatRatio = 1;    // Wichtung, bspw. 25% oder so
                $floatValue = $mixedValue;
                if (is_array($mixedValue))
                {
                    $floatRatio = $mixedValue['ratio'];
                    $floatValue = $mixedValue['value'];
                }

                array_push($tmp_arrValues, ($floatValue-1) * ($floatRatio/$floatRatioSum));
            }

            return (array_sum($tmp_arrValues) + 1);
        }

        /**
         * Math::clamp()
         * bringt einen float Wert auf eine bestimmte Genauigkeit
         * @param float  % der gewuenschten Genauigkeit
         * @return float
         */
        public static function clamp($floatValue, $floatPrecision = 1)
        {
            return round($floatValue * (100/$floatPrecision)) / (100/$floatPrecision);
        }

        public static function clampArraySum($arrValues, $floatSum = 1)
        {
            $arrClampedValues = array();

            if (array_sum($arrValues) == 0)
            {
                foreach( (array)$arrValues as $strKey => $floatValue )
                {
                    $arrClampedValues[$strKey] = (1/count($arrValues)) * $floatSum;
                }
            }
            else
            {
                foreach( (array)$arrValues as $strKey => $floatValue )
                {
                    $arrClampedValues[$strKey] = ($floatSum/array_sum($arrValues)) * $floatValue;
                }
            }

            return $arrClampedValues;
        }
    }