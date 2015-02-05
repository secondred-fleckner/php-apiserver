<?php
    #declare(ENCODING = 'utf-8');
    /**
     * Description of AjaxHandler
     * Werkzeug zum generischen Bau von AjaxAnweisungen für entsprechende Commands,
     * Response wird default im JSON Format erzeugt und über die JS-Fkt. proceedJSONRequest abgearbeitet.
     * @author Martin Fleckner <fleckner@secondred.de>
     * @version 0.1.0
     */

    namespace lib;

    class Ajax
    {
        CONST ASSIGN_HTML   = 'html';
        CONST ASSIGN_VALUE  = 'val';
        CONST ASSIGN_ATTR   = 'attr';

        CONST BASE64_ENCODED_STRINGS = TRUE;

        CONST TYPE_CONFIRM  = 'confirm';
        CONST TYPE_APPEND   = 'append';
        CONST TYPE_BEFORE   = 'before';
        CONST TYPE_AFTER    = 'after';
        CONST TYPE_PREPEND  = 'prepend';
        CONST TYPE_REMOVE   = 'remove';
        CONST TYPE_ALERT    = 'alert';
        CONST TYPE_DEBUG    = 'debug';
        CONST TYPE_OVERLAY  = 'overlay';
        CONST TYPE_PROMPT   = 'prompt';
        CONST TYPE_INFO     = 'info';
        CONST TYPE_SCRIPT   = 'script';
        CONST TYPE_ASSIGN   = 'assign';
        CONST TYPE_REPLACE  = 'replace';
        CONST TYPE_REDIRECT = 'redirect';

        // game relevant
        CONST TYPE_CONTENTBOX       = 'contentbox';

        CONST REMOVE_TYPE_DEFAULT   = 'default';
        CONST REMOVE_TYPE_FADE      = 'fade';
        CONST REMOVE_TYPE_SLIDE     = 'slide';

        public $arrCollection;
        public $objOnBackward;

        public function __construct()
        {
            $this->clear();
        }

        /**
         * ajax::clear()
         * Entfernt alle bisherigen Anweisungen
         * @return Ajax Instance
         */
        public function clear()
        {
            $this->arrCollection = array();
            return $this;
        }

        public function setReverseCommand($objAjax) {
            $this->objOnBackward = $objAjax;
        }

        /**
         * Ajax::confirm()
         * Wirft eine Bestätigungsabfrage
         * @param string Text der Nachfrage (default: "Sind Sie sich sicher?")
         * @param string Überschrift des Fensters (default: "Bestätigung")
         * @param string Beschriftung für den Okay-Button (default: "Ja, weiter")
         * @param string Javascript, der zusätzlich zum Schließen der Box bei Bestätigung ausgeführt werden soll
         * @param string Beschriftung für den Abbrechen-Button (default: "Abbrechen")
         * @param string Javascript, der zusätzlich zum SChließen der Box beim Abbrechen ausgeführt werden soll
         * @return Ajax Instance
         */
        public function confirm($strText='', $strCaption='', $strConfirm='', $jsOnConfirm='', $strAbort='', $jsOnAbort='')
        {
            if ($strCaption == 'undefined' || !$strCaption) { $strCaption = 'Bestätigung'; }
            if ($strText == 'undefined' || !$strText) { $strText = 'Sind Sie sich sicher?'; }
            if ($strConfirm == 'undefined' || !$strConfirm) { $strConfirm = 'Ja, weiter'; }
            if ($strAbort == 'undefined' || !$strAbort) { $strAbort = 'Abbrechen'; }

            $this->add(Ajax::TYPE_CONFIRM, array('content'=>$strText, 'title'=>$strCaption, 'labelConfirm'=>$strConfirm, 'labelAbort'=>$strAbort, 'actionConfirm'=>$jsOnConfirm, 'actionAbort'=>$jsOnAbort));
            return $this;
        }

        /**
         * Ajax::prompt()
         * Wirft eine Bestätigungsabfrage mit zusätzlichem Formular
         * @param string Formularelemente, oder falls keine Action Nachfrage
         * @param string Überschrift des Fensters (default: "Bestätigung")
         * @param string Falls gesetzt wird ein Formular gebaut, falls nicht besteht die Anfrage aus einem einfachen Confirm
         * @return Ajax Instance
         */
        public function prompt($strText='', $strCaption='', $strAction = null)
        {
            if (false && Ajax::BASE64_ENCODED_STRINGS)
            {
                $strText    = base64_encode($strText);
                $strCaption = base64_encode($strCaption);
                $strAction  = base64_encode($strAction);
            }

            if ($strAction)
                $this->add(Ajax::TYPE_PROMPT, array('content'=>$strText, 'title'=>$strCaption, 'action' => $strAction, 'type' => 'formular'));
            else
                $this->add(Ajax::TYPE_PROMPT, array('content'=>$strText, 'title'=>$strCaption));

            return $this;
        }


        /**
         * ajax::redirect()
         * @param <string> Call der ausgeführt werden soll
         * @return Ajax Instance
         */
        public function redirect($strUrl)
        {
            $this->add(Ajax::TYPE_REDIRECT, array('path' => base64_encode($strUrl)));
            return $this;
        }


        /**
         * ajax::append()
         * Fügt eine append-Anweisung hinzu
         * @param <string> jQuery Selector (bspw. ".myClass > li input[name^=vt_]")
         * @param <string> HTML-Content
         * @return Ajax Instance
         */
        public function append($strIdentifier, $strHtml)
        {
            $arrData = array();
            if (Ajax::BASE64_ENCODED_STRINGS)
                $arrData = array('ident'=>$strIdentifier,'content'=>base64_encode($strHtml));
            else
                $arrData = array('ident'=>$strIdentifier,'content'=>$strHtml);

            $this->add(Ajax::TYPE_APPEND, $arrData);
            return $this;
        }

        /**
         * ajax::replaceWith()
         * Fügt eine replaceWith-Anweisung hinzu
         * @param <string> jQuery Selector (bspw. ".myClass > li input[name^=vt_]")
         * @param <string> HTML-Content
         * @return Ajax Instance
         */
        public function replaceWith($strIdentifier, $strHtml)
        {
            if (Ajax::BASE64_ENCODED_STRINGS)
                $arrData = array('ident'=>$strIdentifier,'content'=>base64_encode($strHtml));
            else
                $arrData = array('ident'=>$strIdentifier,'content'=>Ajax::filter($strHtml));

            $this->add(Ajax::TYPE_REPLACE, $arrData);
            return $this;
        }

        private function add($strType, $arrData) {
            array_push($this->arrCollection, array('type'=>$strType, 'data'=>$arrData));
        }
        
        public function addAjax($objAjax) {
            $this->arrCollection = array_merge((array)$this->arrCollection, (array)$objAjax->arrCollection);
        }

        /**
         * ajax::after()
         * Fügt eine after-Anweisung hinzu
         * @param <string> jQuery Selector (bspw. ".myClass > li input[name^=vt_]")
         * @param <string> HTML-Content
         * @return Ajax Instance
         */
        public function after($strIdentifier, $strHtml)
        {
            $arrData = array();
            if (Ajax::BASE64_ENCODED_STRINGS)
                $arrData = array('ident'=>$strIdentifier,'content'=>base64_encode($strHtml));
            else
                $arrData = array('ident'=>$strIdentifier,'content'=>$strHtml);

            $this->add(Ajax::TYPE_AFTER, $arrData);
            return $this;
        }

        /**
         * ajax::before()
         * Fügt eine before-Anweisung hinzu
         * @param <string> jQuery Selector (bspw. ".myClass > li input[name^=vt_]")
         * @param <string> HTML-Content
         * @return Ajax Instance
         */
        public function before($strIdentifier, $strHtml)
        {
            $arrData = array();
            if (Ajax::BASE64_ENCODED_STRINGS)
                $arrData = array('ident'=>$strIdentifier,'content'=>base64_encode($strHtml));
            else
                $arrData = array('ident'=>$strIdentifier,'content'=>$strHtml);

            $this->add(Ajax::TYPE_BEFORE, $arrData);
            return $this;
        }

        /**
         * ajax::remove()
         * Fügt eine remove-Anweisung hinzu
         * @param <string> jQuery Selector (bspw. ".myClass > li input[name^=vt_]")
         * @param <string> Type (default,fade,slide)
         * @param <string> Javascript, der für jedes entfernte Objekt ausgeführt wird
         * @return Ajax Instance
         */
        public function remove($strIdentifier, $strType=ajax::REMOVE_TYPE_DEFAULT, $strCallback='')
        {
            $arrData = array();
            $arrData = array('ident'=>$strIdentifier,'type'=>$strType,'callback'=>base64_encode($strCallback));
            $this->add(Ajax::TYPE_REMOVE, $arrData);
            return $this;
        }

        /**
         * ajax::prepend()
         * Fügt eine prepend-Anweisung hinzu
         * @param <string> jQuery Selector (bspw. ".myClass > li input[name^=vt_]")
         * @param <string> HTML-Content
         * @return Ajax Instance
         */
        public function prepend($strIdentifier, $strHtml)
        {
            $arrData = array();
            if (Ajax::BASE64_ENCODED_STRINGS)
                $arrData = array('ident'=>$strIdentifier,'content'=>base64_encode($strHtml));
            else
                $arrData = array('ident'=>$strIdentifier,'content'=>$strHtml);

            $this->add(Ajax::TYPE_PREPEND, $arrData);
            return $this;
        }


        /**
         * ajax::debug()
         * Gibt einen Text per console.log nach Ausführung aller Anweisungen aus
         * @param <string> Ausgabetext
         * @return Ajax Instance
         */
        public function debug($strText)
        {
            $strData = '';
            if (Ajax::BASE64_ENCODED_STRINGS)
                $strData = base64_encode($strText);
            else
                $strData = $strText;

            $this->add(Ajax::TYPE_DEBUG, $strData);
            return $this;
        }

        /**
         * ajax::alert()
         * Gibt einen Text per alert() vor Ausführung weiterer Anweisungen aus
         * @param <string> Ausgabetext
         * @param <string> Fensterüberschrift
         * @return Ajax Instance
         */
        public function alert($strTitle='Benachrichtigung', $strImage='', $strText='', $intDuration = 3000)
        {
            $arrData = array();
            if (Ajax::BASE64_ENCODED_STRINGS)
            {
                $arrData = array(   'text' => base64_encode($strText),
                                    'title' => base64_encode($strTitle),
                                    'image' => base64_encode($strImage),
                                    'duration' => intval($intDuration) );
            }
            else
            {
                $arrData = array(   'text' => $strText,
                                    'title' => $strTitle,
                                    'image' => $strImage,
                                    'duration' => intval($intDuration) );
            }

            $this->add(Ajax::TYPE_ALERT, $arrData);
            return $this;
        }


        /**
         * ajax::script()
         * Führt Javascript am Ende des Anweisungsblockes aus. Eventuell vorhergehende Anweisungen wurden in die DOM bereits integriert und werden somit berücksichtigt
         * Mit ajax::debug() ausgeführte Anweisungen werden danach ausgeführt
         * @param <string> Javascript der ausgeführt werden soll
         * @return Ajax Instance
         */
        public function script($strJavascript)
        {
            $strData = '';
            if (Ajax::BASE64_ENCODED_STRINGS)
                $strData = base64_encode($strJavascript);
            else
                $strData = base64_encode($strJavascript);

            $this->add(Ajax::TYPE_SCRIPT, $strData);
            return $this;
        }

        /**
         * Ajax::overlay()
         * Erzeugt ein frei definierbares einspaltiges Overlayformular
         * @param mixed $mixedContent HTML-Inhalt der Box
         * @param mixed $mixedCaption Überschrift der Box
         * @param boolean $bOverlayCover (default=false) Falls true wird hinter dem Overlay eine halbtransparente Abdeckung eingeblendet, welche das Klicken auf dehinter liegende Objekte unterbindet
         * @param array $arrSize array(width,height) bspw. array(800,0) erzeugt Overlay mit 800px breite und variabler Höhe, falls null|false gegeben wird Standardgröße festgelegt
         * @param array $arrOptions Liste von Optionen bspw. array('text'=>'Speichern','script'=>'saveForm("xyz"))', ['align'=>'left|right'], ['classes'=>array('class1','class2') od. 'class1'])
         * @param boolean $bSplitted Default=false, gibt an ob das Overlay in 2 Bereiche unterteilt wird (falls ja, werden 1. und 2. Parameter als Array mit jeweils 2 Werten interpretiert, [0] ist linker Bereich, [1] ist der Rechte)
         * @return Ajax Instance
         */
        public function overlay($mixedContent, $mixedCaption='Fenster', $bOverlayCover=false, $arrSize=array(662,0), $arrOptions=array(  array('text'=>'Okay', 'align'=>'right', 'classes'=>'optionOkay', 'script'=>'$(this).closest(".overlay").closeOverlay();')), $bSplitted=false)
        {
            $arrData = array();
            if (!is_array($arrSize) || count($arrSize) == 0 || ($arrSize[0] <= 0 && (count($arrSize)>1 && $arrSize[1] <= 0)) )
                $arrSize = array(662,0);

            if ($bSplitted)
                $arrData = array('content'=>$mixedContent[0], 'content_right'=>$mixedContent[1], 'caption'=>$mixedCaption[0], 'caption_right'=>$mixedCaption[1], 'cover'=>$bOverlayCover, 'size'=>$arrSize, 'options'=>$arrOptions,'splitted'=>1);
            else
                $arrData = array('content'=>$mixedContent, 'caption'=>$mixedCaption, 'cover'=>$bOverlayCover, 'size'=>$arrSize, 'options'=>$arrOptions, 'splitted'=>0);

            $this->add(Ajax::TYPE_OVERLAY, $arrData);
            return $this;
        }

        /**
         * Ajax::info()
         * Erzeugt ein Info Overlay mit inhalt
         * @param mixed $mixedContent HTML-Inhalt der Box
         * @param mixed $mixedCaption Überschrift der Box
         * @return Ajax Instance
         */
        public function info($mixedContent, $mixedCaption='Information')
        {
            if (Ajax::BASE64_ENCODED_STRINGS)
            {
                $arrData = array('content'=> base64_encode($mixedContent), 'title'=>base64_encode($mixedCaption));
            }
            else
            {
                $arrData = array('content'=>$mixedContent, 'title'=>$mixedCaption);
            }
            $this->add(Ajax::TYPE_INFO, $arrData);
            return $this;
        }


        /**
         * ajax::assign()
         * Ersetzt den innerHTML, den value oder ein Attribut einer Auswahl
         * @param <string> jQuery Selector (bspw. ".myClass > li input[name^=vt_]")
         * @param <string> neuer Content/Wert
         * @param <string> [default=ASSIGN_HTML] Typ als CONST-enum verfügbar, ajax::ASSIGN_HTML, ajax::ASSIGN_VALUE, ajax::ASSIGN_ATTR
         * @param <string> [default=name] Attribut, falls Typ=ASSIGN_ATTR wird hier der Attribut-Key spezifiziert bspw. "alt"
         * @return Ajax Instance
         */
        public function assign($strIdentifier, $strContent, $strType=ajax::ASSIGN_HTML, $strAttribute='name')
        {
            /*if (!is_array($this->arrAssign[$strType]))
            {
                $this->arrAssign[$strType] = array();
            }*/

            if ($strType != ajax::ASSIGN_ATTR)
            {
                if (Ajax::BASE64_ENCODED_STRINGS)
                    $arrAssignment = array('ident'=>$strIdentifier,'content'=>base64_encode($strContent));
                else
                    $arrAssignment = array('ident'=>$strIdentifier,'content'=>$strContent);
            }
            else
            {
                if (Ajax::BASE64_ENCODED_STRINGS)
                    $arrAssignment = array('ident'=>$strIdentifier,'content'=>base64_encode($strContent),'attr'=>$strAttribute);
                else
                    $arrAssignment = array('ident'=>$strIdentifier,'content'=>$strContent,'attr'=>$strAttribute);
            }

            //array_push($this->arrAssign[$strType], $arrAssignment);
            $this->add(Ajax::TYPE_ASSIGN.'_'.$strType, $arrAssignment);
            return $this;
        }

        public function contentBox($strBoxIdentifier, $strContent)
        {
            if (Ajax::BASE64_ENCODED_STRINGS)
                $arrAssignment = array('ident'=>$strBoxIdentifier,'content'=>base64_encode($strContent));
            else
                $arrAssignment = array('ident'=>$strBoxIdentifier,'content'=>$strContent);

            $this->add(Ajax::TYPE_CONTENTBOX, $arrAssignment);
            return $this;
        }


        /**
         * ajax::getResponse()
         * Erzeugt den eigentlich Anweisungsblock, der zur Weiterverarbeitung bestimmt wird.
         * @param <bool> (optional) [default=true] $bEcho Falls true wird JSON oder Serialisiertes Array ausgegeben
         * @param <bool> (optional) [default=true] $bJsonEncode Falls true wird Ausgabe JSON-enkodiert
         * @return <array> Falls !$bEcho als array[assign,after,append,before,debug,prepend,script] wenn $bJsonEncode=false, sonst <string> JSON-encoded
         */
        public function getResponse($bEcho=true, $bJsonEncode=true)
        {
            global $SESSION;
            $arrCollection['commands'] = $this->arrCollection /*array( 'assign'=>$this->arrAssign,
                                    'after'=>$this->arrAfter,
                                    'append'=>$this->arrAppend,
                                    'before'=>$this->arrBefore,
                                    'debug'=>$this->arrDebug,
                                    'prepend'=>$this->arrPrepend,
                                    'script'=>$this->arrScript,
                                    'alert'=>$this->arrAlert,
                                    'confirm'=>$this->arrConfirm,
                                    'overlays'=>$this->arrOverlays)*/;
            if ($this->objOnBackward) {

                $arrCollection['onBackward'] = $this->objOnBackward;
            }

            $mixedReturn = ($bJsonEncode) ? json_encode($arrCollection) : $arrCollection;

            if ($bEcho && $bJsonEncode)
            {
                if ($this->objOnBackward) {
                    $tmp_arrHistory = $_SESSION['ajaxHistory'];
                    #$SESSION->push('ajaxHistory', $mixedReturn);
                    $tmp_arrHistory[intval($_SESSION['ajax_history_last_index'])] = $mixedReturn;

                    array_splice($tmp_arrHistory, intval($_SESSION['ajax_history_last_index'])+1);
                    $_SESSION['ajaxHistory'] = $tmp_arrHistory;

                    echo 'history-'.count($_SESSION['ajaxHistory']);
                } else {
                    echo $mixedReturn;
                }
            }
            else if ($bEcho && is_array($mixedReturn))
            {
                echo serialize($mixedReturn);
            }
            else
            {
                if ($this->objOnBackward) {
                    $tmp_arrHistory = $_SESSION['ajaxHistory'];
                    #$SESSION->push('ajaxHistory', $mixedReturn);
                    $tmp_arrHistory[intval($_SESSION['ajax_history_last_index'])] = $mixedReturn;
                    array_splice($tmp_arrHistory, intval($_SESSION['ajax_history_last_index'])+1);
                    $_SESSION['ajaxHistory'] = $tmp_arrHistory;
                    return 'history-'.count($_SESSION['ajaxHistory']);
                } else {
                    return $mixedReturn;
                }
            }

            #exit();
        }

        public function getInlineScript($bOnDocumentReady = true) {
            $strReturn = '<script type="text/javascript">';

            if ($bOnDocumentReady)
                $strReturn .= '$(document).ready(function () {';

            $strReturn .= 'try { var jsonObj = $.parseJSON(\''.$this->getResponse(false, true).'\'); processAjaxJson(jsonObj, true); } catch(e) { console.log("Ajax-Parsing fehlgeschlagen"); }';

            if ($bOnDocumentReady)
                $strReturn .= '});';

            $strReturn .= '</script>';
            return $strReturn;
        }


        /**
         * ajax::isRelevant()
         * Gibt true|false, ob eine Anweisung existiert
         * @return <bool> ob eine Anweisung existiert
         */
        public function isRelevant()
        {
            return (count($this->arrCollection) > 0);

            if (  count($this->arrAfter) + count($this->arrAppend) + count($this->arrAssign) + count($this->arrBefore)
                + count($this->arrDebug) + count($this->arrPrepend) + count($this->arrScript) + count($this->arrAlert) + count($this->arrOverlays) > 0 && is_array($this->arrConfirm))
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public static function filter($strContent) {
            return $strContent;
            return str_replace(array("\n","\r","\""), array('','','\\"'), $strContent);
        }
    }