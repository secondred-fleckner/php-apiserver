<?php
/////////////////////////////////////////////////
// phpmailer - PHP email class
// 
// Version 0.9, 04/16/2001
//
// Class for sending email using either 
// sendmail, PHP mail(), or SMTP.  Methods are
// based upon the standard AspEmail(tm) classes.
//
// Author: Brent R. Matzelle
//
// License: LGPL, see LICENSE
/////////////////////////////////////////////////

/* $oMail = new PHPMailer;
    $oMail->IsMail();
    $oMail->From = 'game@genesis-projekt.net';
    $oMail->FromName = 'Das Genesis Projekt 3.0beta';
    $oMail->Host = 'smtp.genesis-projekt.net';
    $oMail->AddAddress('mahdi@genesis-projekt.net');
    $oMail->AddAddress('fleckner@secondred.de');
    $oMail->IsHTML(true);
    $oMail->Subject = 'Testmessage';
    $oMail->Body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html style="padding:0;margin:0;">
<head>
<title>my world - my bernette - SECONDRED Newsletter</title>
<meta name="author" content="SECONDRED"> 
<meta name="copyright" content="SECONDRED"> 
<meta name="language" content="de"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <style media="all" type="text/css">
  table td {
    border-collapse: collapse;
  }
  </style>

</head>

<body style="font-size:12px; font-family:Arial, Helvetica, sans-serif;margin:0;padding:0;color:#464646;background:#282828;">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
 <tr>
  <td width="50%"></td>
  <td>
		<table cellspacing="0" cellpadding="0" border="0" width="100%" style="padding:0;margin:0;background:#282828;">
				<tr height="96">
				<td colspan="3">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="368"><a href="http://www.secondred.de" title="zur SECONDRED Website"><img src="http://www.secondred.de/newsletter/gfx/bg_head_logo.jpg" alt="SECONDRED" title="zur SECONDRED Website" style="border:none;display:block;"/></a></td>
							<td width="207" align="right"><p style="font-size:12px;color:#666666;font-family:Arial, Helvetica, sans-serif;" title="my world - my bernette">my world - my bernette</p></td>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_head_spacer_right.gif"/></td>
						</tr>
					</table>
					</td>
			</tr>
			<tr>
	<td width="0"></td>
	<td width="564">
		<a name="punkt1" style="padding:0px;margin:0px;font-size:0px;display:inline"></a>
		<table cellspacing="0" cellpadding="0" style="border:2px solid #232323;border-bottom:0px;border-color:#232323;"  bgcolor="#ffffff">
			<tr>
				<td colspan="3"><a class="link" style="text-decoration:none;" target="_blank" href="http://www.secondred.de/Projekte/BERNINA-bernette-Website/244,129.html" title="Mehr zur neuen bernette Website erfahren"><img alt="my world - my bernette" src="http://www.secondred.de/cms/getimage.php?5958.jpg" width="560" height="250" style="border:0px"></a></td>
			</tr>
			<tr bgcolor="#FFFFFF">						
				<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>	
				<td width="500"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>

				<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>					
			</tr>
			<tr bgcolor="#FFFFFF">						
				<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>	
				<td width="500">
					<p style="font-size:27px;color:#930000;font-family:Arial, Helvetica, sans-serif;"><a class="link" style="font-size:27px;color:#930000;text-decoration:none;" target="_blank" href="http://www.secondred.de/Projekte/BERNINA-bernette-Website/244,129.html" title="Mehr zur neuen bernette Website erfahren">Die neue bernette im blo:c design</a><br /><span  style="font-size:16px;color:#930000">Internationaler Produktlaunch für BERNINA</span></p>
					<p style="font-size:12px;font-family:Arial, Helvetica, sans-serif;color:#464646;font-weight:bold;">Sehr geehrter Martin Fleckner,<br/></p>

<p style="font-size:12px;font-family:Arial, Helvetica, sans-serif;color:#464646;">SECONDRED launcht f&uuml;r den Premium-N&auml;hmaschinenhersteller BERNINA International AG die Produkt-Website der neuen bernette. Mit der Idee und dem Konzept der gesamten Kampagne stellt SECONDRED wieder einmal mehr die Kreativit&auml;t der Agentur unter Beweis. Der Produkt-Rollout geschieht auf internationaler Ebene, ebenso wie die L&auml;nder- und Sprachvielfalt der Website. F&uuml;r h&ouml;chste Flexibilit&auml;t kam das Content Management System (CMS) der SECONDRED zum Einsatz. Mit der revolution&auml;ren Flashtechnologie im Hintergrund der Website wird die junge Zielgruppe der neuen bernette optimal angesprochen. Au&szlig;erdem kann der Nutzer kreative N&auml;hprojekte herunterladen.</p>
				  				  <p><a class="link" style="font-size:12px; font-family:Arial, Helvetica, sans-serif;color:#464646;font-weight:bold;text-decoration:none;" target="_blank" href="http://www.secondred.de/Projekte/BERNINA-bernette-Website/244,129.html" title="Mehr zur neuen bernette Website erfahren">Mehr zur neuen bernette Website erfahren <img src="http://www.secondred.de/newsletter/gfx/arrow.gif" alt="Mehr zur neuen bernette Website erfahren" style="border:none;"/></a></p>
				  				
					<br><br>
				</td>
				<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>					
			</tr>
		</table>
	</td>
	<td></td>

</tr><tr>
	<td width="0"></td>
	<td width="564">
		<table cellspacing="0" cellpadding="0" border="0" style="border: 2px solid #232323;border-top:0px;border-bottom:0px;border-color:#232323;width:100%"  bgcolor="#ffffff">
			<tr>
				<td><a class="link" style="text-decoration:none;" target="_blank" href="http://www.pastarie.com/Pastakreationen/Faschingspasta/118/" title="Jetzt Faschings-Nudel bestellen"><img alt="" src="http://www.secondred.de/cms/getimage.php?5968.jpg" width="279" height="190" style="border:0px"></a></td>
				<td><img src="http://www.secondred.de/newsletter/gfx/spacer_linie_news.jpg"/></td>
				<td><a class="link" style="text-decoration:none;" target="_blank" href="http://www.secondred.de/Projekte/ForumK-Webseite/244,89.html" title="Mehr zur ForumK erfahren"><img alt="ForumK - Kulturmesse" src="http://www.secondred.de/cms/getimage.php?5960.jpg" width="279" height="189" style="border:0px"></a></td>
			</tr>
			<tr>
				<td bgcolor="#EEEEEE">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="100%"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
						</tr>
					</table>
				</td>
				<td bgcolor="#EEEEEE"></td>
				<td bgcolor="#EEEEEE">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="100%"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr valign="top">
				<td bgcolor="#EEEEEE">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="100%">										
								<p style="font-size:14px;color:#930000;font-family:Arial, Helvetica, sans-serif;font-weight:bold;"><a class="link" style="font-size:14px;color:#930000;text-decoration:none;" target="_blank" href="http://www.pastarie.com/Pastakreationen/Faschingspasta/118/" title="Jetzt Faschings-Nudel bestellen">Vernarrt in Pasta</a></p>
								<p style="font-size:12px;font-family:Arial, Helvetica, sans-serif;color:#464646;">Das Ende der n&auml;rrischen Zeit r&uuml;ckt n&auml;her. Pastarie hat zu diesem Anlass vier verr&uuml;ckte Pastakreationen zusammengestellt, die ab sofort im Pastarie Onlineshop unter www.pastarie.com zu bestellen sind. SECONDRED betreut Pastarie in den Bereichen Konzeption, Gestaltung, Shop-Entwicklung und Online-Marketing.</p>
							  <p><a class="link" style="font-size:12px; font-family:Arial, Helvetica, sans-serif;color:#464646;font-weight:bold;text-decoration:none;" target="_blank" href="http://www.pastarie.com/Pastakreationen/Faschingspasta/118/" title="Jetzt Faschings-Nudel bestellen">Jetzt Faschings-Nudel bestellen <img src="http://www.secondred.de/newsletter/gfx/arrow.gif" alt="Jetzt Faschings-Nudel bestellen" style="border:none;"/></a></p>
 							  </td>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
						</tr>
					</table>
				</td>
				<td bgcolor="#D6D6D6"></td>
				<td bgcolor="#EEEEEE">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="100%">
								<p style="font-size:14px;color:#930000;font-family:Arial, Helvetica, sans-serif;font-weight:bold;"><a class="link" style="font-size:14px;color:#930000;text-decoration:none;" target="_blank" href="http://www.secondred.de/Projekte/ForumK-Webseite/244,89.html" title="Mehr zur ForumK erfahren">Sponsor für ForumK</a></p>
								<p style="font-size:12px;font-family:Arial, Helvetica, sans-serif;color:#464646;">Auch in diesem Jahr unterst&uuml;tzt SECONDRED wieder die Kultur mit einem Sponsoring der mittlerweile bundesweit hoch angesehenen Kulturmesse ForumK in Suhl. Am 20. April 2011 findet die Messe statt, ein Besuch lohnt sich vor allem f&uuml;r Veranstalter. SECONDRED betreut den gesamten Onlineauftritt und Onlinemarketing der ForumK.</p>
								<p><a class="link" style="font-size:12px; font-family:Arial, Helvetica, sans-serif;color:#464646;font-weight:bold;text-decoration:none;" target="_blank" href="http://www.secondred.de/Projekte/ForumK-Webseite/244,89.html" title="Mehr zur ForumK erfahren">Mehr zur ForumK erfahren <img src="http://www.secondred.de/newsletter/gfx/arrow.gif" alt="Mehr zur ForumK erfahren" style="border:none;"/></a></p>
								</td>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr style="border-bottom:1px solid #E4E4E4;">
				<td bgcolor="#EEEEEE">
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="100%"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
						</tr>
					</table>
				</td>
				<td bgcolor="#EEEEEE"></td>
				<td bgcolor="#EEEEEE" >
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="100%"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_news.gif"/></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
	<td></td>

</tr><tr>
				<td width="0"></td>
				<td width="564">
					<table cellspacing="0" cellpadding="0" border="0" style="border: 2px solid #232323;border-top:0px;border-color:#232323;" bgcolor="#ffffff">		
						<tr>
							<td bgcolor="#FFFFFF">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="100%"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
									</tr>
								</table>
							</td>
							<td bgcolor="#FFFFFF">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="100%"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF" style="width:280px; vertical-align: top;">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="100%">
											<p style="font-size:14px;color:#930000;font-family:Arial, Helvetica, sans-serif;font-weight:bold;">Aktuelles</p>		
											<p style="line-height:18px;color:#666666;font-size:12px;font-family:Arial, Helvetica, sans-serif;color:#464646;">								
												<a class="link" style="color:#666666;text-decoration:none;" href="http://www.secondred.de/News/iPhone-App-fuer-medfuehrer/257,299.html" title="iPhone-App für medführer"><strong>14.02.2011</strong> iPhone-App für...</a><br /><a class="link" style="color:#666666;text-decoration:none;" href="http://www.secondred.de/News/Produktwebsite-fuer-BERNINA/257,300.html" title="Produktwebsite für BERNINA"><strong>01.02.2011</strong> Produktwebsite für...</a><br /><a class="link" style="color:#666666;text-decoration:none;" href="http://www.secondred.de/News/Stiftung-Haeuser-fuer-die-Welt-eV-geht-online/257,298.html" title="Stiftung Häuser für die Welt e.V. geht online"><strong>17.01.2011</strong> Stiftung Häuser für...</a><br />







































											</p>
										</td>
										<td width="30"  style="border-right:1px solid #D6D6D6;"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
									</tr>
								</table>
							</td>
							<td bgcolor="#FFFFFF" style="width:280px;vertical-align: top;">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="100%">
										<p style="font-size:14px;color:#930000;font-family:Arial, Helvetica, sans-serif;font-weight:bold;">Blog</p>		
											<p style="line-height:18px;color:#666666;font-size:12px;font-family:Arial, Helvetica, sans-serif;color:#464646;">								
												<a class="link" style="color:#666666;text-decoration:none;" href="http://www.secondred.de/Blog/Generative-Gestaltung-reloaded/282,67.html" title="Generative Gestaltung reloaded"><strong>21.02.2011</strong> Generative Gestaltung...</a><br /><a class="link" style="color:#666666;text-decoration:none;" href="http://www.secondred.de/Blog/Headnotes/282,66.html" title="Headnotes"><strong>25.01.2011</strong> Headnotes</a><br /><a class="link" style="color:#666666;text-decoration:none;" href="http://www.secondred.de/Blog/Teambuilding/282,65.html" title="Teambuilding"><strong>20.01.2011</strong> Teambuilding</a><br />







































											</p>
										</td>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr style="border-bottom:1px solid #D6D6D6;">
							<td bgcolor="#FFFFFF">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="100%"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
									</tr>
								</table>
							</td>
							<td bgcolor="#FFFFFF">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="100%"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
										<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_spacer_content.gif"/></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
                    
				</td>
                <td width="0"></td>
			</tr>

	<!--<tr height="30">			
				<td width="0"><img width="0" src="http://www.secondred.de/newsletter/gfx/bg_head_spacer_right.gif"/></td>
				<td width="564"><img width="43" src="http://www.secondred.de/newsletter/gfx/bg_head_spacer_right.gif"/></td>
				<td></td>		
			</tr>-->
				<td width="0"></td>
				<td width="564">
					<table cellspacing="0" cellpadding="0" border="0" style="border: 2px solid #232323;border-top:0px;border-color:#232323;" bgcolor="#ffffff">		
						<tr>
							<td bgcolor="#FFFFFF" colspan="4">
								<a href="http://www.facebook.com/secondred" title="SECONDRED auf Facebook"><img src="http://www.secondred.de/newsletter/gfx/social_facebook.jpg" alt="Facebook" style="border:none;"/></a><a href="http://www.xing.com/companies/SECONDREDNEWMEDIAGMBH" title="SECONDRED bei XING"><img src="http://www.secondred.de/newsletter/gfx/social_xing.jpg" alt="XING" style="border:none;"/></a><a href="http://twitter.com/SECONDRED" title="SECONDRED auf Twitter"><img src="http://www.secondred.de/newsletter/gfx/social_twitter.jpg" alt="Twitter" style="border:none;"/></a><a href="http://blog.secondred.de" title=""SECONDRED Blog><img src="http://www.secondred.de/newsletter/gfx/social_blog.jpg" alt="SECONDRED Blog" style="border:none;"/></a>
							</td>      
						</tr>
					</table>
				</td>
                <td width="0"></td>
			</tr>
				
			<tr>			
				<td width="30"><img width="30" src="http://www.secondred.de/newsletter/gfx/bg_head_spacer_right.gif"/></td>
				<td width="564">
					<table  cellspacing="0" cellpadding="0" border="0" style="background:#353535;">
						<tr>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_footer_spacer_right.gif"/></td>
							<td style="color:#FFFFFF;" width="250" align="left" valign="top">
							<p style="font-size:10px;color:#FFFFFF;font-family:Arial, Helvetica, sans-serif;">							
								<br>
								<br>
                                <span style="font-size:11px;"><strong>
								Impressum</strong></span><br>
						    <br>
								SECONDRED Newmedia GmbH<br><br>
								Die Agentur für Ihre individuellen Web- und IT-Projekte, Online-Kampagnen und Applikationen.<br><br>
								Peterstraße 5<br>
								99084 Erfurt<br><br>
								Tel.:  +49 (0) 361 / 2279846<br>
								Fax:  +49 (0) 361 / 2168699<br><br>
								Internet: <a href="http://www.secondred.de" title="Zur SECONDRED Website" style="color:#FFFFFF;text-decoration:none;">www.secondred.de</a><br>
								E-Mail: <a href="mailto:post@secondred.de" title="E-Mail an SECONDRED senden" style="color:#FFFFFF;text-decoration:none;">post@secondred.de</a>
								</p>
						  </td>
							<td style="color:#FFFFFF;" width="104" align="left">
							<p style="font-size:10px;color:#FFFFFF;font-family:Arial, Helvetica, sans-serif;">
								<br>							
							</p>
							</td>
							<td style="color:#999999;" width="250" align="left" valign="top">
							<p style="font-size:10px;color:#FFFFFF;font-family:Arial, Helvetica, sans-serif;">							
								<br>
								<br>
								<br>
								<br>
								<br>
								<br>
			          Bankverbindung<br>
								SPK Mittelthüringen<br>
								Konto 013 005 948 0<br>
								BLZ 820 510 00<br><br>
								 
								Registergericht Jena<br>
								HRB 12474<br>
								Stnr. 151 / 118 / 05528<br>
								UST-ID: DE223767570<br><br>
								 
								Geschäftsführung<br>
								René Linz
                                [[VIEWIMG]]
								</p>
						  </td>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_footer_spacer_right.gif"/></td>
						</tr>
						
						<tr>
							<td><img src="http://www.secondred.de/newsletter/gfx/bg_footer_spacer_right.gif"/></td>
							<td colspan="3" style="color:#808080;" width="100%" align="left">
							<p style="font-size:10px;color:#808080;font-family:Arial, Helvetica, sans-serif;">
								<br>
						    Wenn diese E-Mail nicht richtig dargestellt wird, klicken Sie bitte <a href="http://www.secondred.de/cms/?s=NewsletterPreview&newsletterID=16&pvkey=%A7umu8NTI0Ng%3D%3D5" alt="im Browser ansehen" style="color:#909090;">hier.</a><br>
								Möchten Sie keinen Newsletter mehr von SECONDRED erhalten, dann tragen Sie sich <a href="http://www.secondred.de/cms/?s=unsubscribe&nid=482&ecm=%C4VXbWFoZGlAZ2VuZXNpcy1wcm9qZWt0Lm5ldA%3D%3D3" title="Abmelden" style="color:#909090;">hier</a> aus<img src="http://www.secondred.de/nlview/5e3f82958b5322d8d66280f2736a33fd_5246.gif" height="2" width="2" />.<br><br>
© 2011 SECONDRED Newmedia GmbH				
							</p>
						  </td>
							<td width="30"><img src="http://www.secondred.de/newsletter/gfx/bg_footer_spacer_right.gif"/></td>
						</tr>
					</table>
				</td>
				<td></td>		
			</tr>
		</table>
	</td>
  <td width="50%"></td>

</tr>
</table>
</body>
</html>';
    $oMail->Send();*/

namespace lib;

class PHPMailer
{
	/////////////////////////////////////////////////
	// CLASS VARIABLES
	/////////////////////////////////////////////////
	
	// General Variables
	var $Priority    = 3;
	var $CharSet     = "utf8";
	var $ContentType = "text/plain";
	var $Encoding    = "8bit";
	var $From        = "root@localhost";
	var $FromName    = "root";
	var $to          = array();
	var $cc          = array();
	var $bcc         = array();
	var $ReplyTo     = array();
	var $Subject     = "";
	var $Body        = "";
	var $WordWrap    = false;
	var $mailer      = "mail";
	var $sendmail    = "/usr/lib/sendmail";
	var $attachment  = array();
	var $boundary    = false;
	var $MailerDebug = true;

	// SMTP-specific variables
	var $Host        = "localhost";
	var $Port        = 25;
	var $Helo        = "localhost.localdomain";
	var $Timeout     = 10; // Socket timeout in sec.
	var $SMTPDebug   = false;
	

	/////////////////////////////////////////////////
	// VARIABLE METHODS
	/////////////////////////////////////////////////

	// Sets message to HTML
	function IsHTML($bool) {
		if($bool == true)
			$this->ContentType = "text/html";
		else
			$this->ContentType = "text/plain";
	}

	// Sets mailer to use SMTP
	function IsSMTP() {
		$this->mailer = "smtp";
	}

	// Sets mailer to use PHP mail() function
	function IsMail() {
		$this->mailer = "mail";
	}

	// Sets mailer to directly use $sendmail program
	function IsSendmail() {
		$this->mailer = "sendmail";
	}

	// Sets $sendmail to qmail MTA
	function IsQmail() {
		$this->sendmail = "/var/qmail/bin/qmail-inject";
	}


	/////////////////////////////////////////////////
	// RECIPIENT METHODS
	/////////////////////////////////////////////////	

	// Add a "to" address
	function AddAddress($address, $name = "") {
		$cur = count($this->to);
		$this->to[$cur][0] = trim($address);
		$this->to[$cur][1] = $name;
	}
	
	// Add a "cc" address
	function AddCC($address, $name = "") {
		$cur = count($this->cc);
		$this->cc[$cur][0] = trim($address);
		$this->cc[$cur][1] = $name;
	}
	
	// Add a "bcc" address
	function AddBCC($address, $name = "") {
		$cur = count($this->bcc);
		$this->bcc[$cur][0] = trim($address);
		$this->bcc[$cur][1] = $name;
	}
	
	// Add a "Reply-to" address
	function AddReplyTo($address, $name = "") {
		$cur = count($this->ReplyTo);
		$this->ReplyTo[$cur][0] = trim($address);
		$this->ReplyTo[$cur][1] = $name;
	}


	/////////////////////////////////////////////////
	// MAIL SENDING METHODS
	/////////////////////////////////////////////////
	
	// Create message and assign to mailer
	function Send($database=true) {
        global $DB;
		if(count($this->to) < 1)
			$this->error_handler("You must provide at least one recipient email address");

		$header = $this->create_header();
		$body = $this->create_body();
        
        if ($database)
        {            
            $tmp_arrTo = $this->to;        
            foreach ($tmp_arrTo as $arrTo)
            {                
                $this->to = array();
                $this->to[0] = $arrTo;
                
                $DB->query("INSERT INTO ".$DB->db_prefix."newsletter_sent (receiver, sent) VALUES ('".$arrTo[0]."','1');");      
                $newsletterSendId = $DB->getLastID();      
                $body_viewimg = str_replace('[[VIEWIMG]]', '<img src="http://'.$_SERVER['HTTP_HOST'].'/newsletterview/'.md5(time()).'_'.$newsletterSendId.'.gif" alt="viewimg" height="1" width="1" />', $body);
                
                if($this->mailer == "sendmail")
        			$this->sendmail_send($header, $body_viewimg);
        		elseif($this->mailer == "mail")
        			$this->mail_send($header, $body_viewimg);
        		elseif($this->mailer == "smtp")
        			$this->smtp_send($header, $body_viewimg);
        		else
        			$this->error_handler(sprintf("%s mailer is not supported", $this->mailer));
            }
        }
		else
        {
            	// Choose the mailer
    		if($this->mailer == "sendmail")
    			$this->sendmail_send($header, $body);
    		elseif($this->mailer == "mail")
    			$this->mail_send($header, $body);
    		elseif($this->mailer == "smtp")
    			$this->smtp_send($header, $body);
    		else
    			$this->error_handler(sprintf("%s mailer is not supported", $this->mailer));
        }
	}
	
	// Send using the $sendmail program
	function sendmail_send($header, $body) {
		$sendmail = sprintf("%s -f %s -t", $this->sendmail, $this->From);

		if(!$mail = popen($sendmail, "w"))
			$this->error_handler(sprintf("Could not open %s", $this->sendmail));
		
		fputs($mail, $header);
		fputs($mail, $body);
		pclose($mail);
	}
	
	// Send via the PHP mail() function
	function mail_send($header, $body) {
		// Create mail recipient list
		$to = $this->to[0][0]; // no extra comma
		for($x = 1; $x < count($this->to); $x++)
			$to .= sprintf(",%s", $this->to[$x][0]);
		for($x = 0; $x < count($this->cc); $x++)
			$to .= sprintf(",%s", $this->cc[$x][0]);
		for($x = 0; $x < count($this->bcc); $x++)
			$to .= sprintf(",%s", $this->bcc[$x][0]);
		
		if(!mail($to, $this->Subject, $body, $header))
			$this->error_handler("Could not instantiate mail()");
	}
	
	// Send message via SMTP using PhpSMTP
	// PhpSMTP written by Chris Ryan
	function smtp_send($header, $body) {
		include("smtp.inc.php"); // Load code only if asked
		$smtp = new SMTP;
		$smtp->do_debug = $this->SMTPDebug;
		
		// Try to connect to all SMTP servers
		$hosts = explode(";", $this->Host);
		$x = 0;
		$connection = false;
		while($x < count($hosts))
		{
			if($smtp->Connect($hosts[$x], $this->Port, $this->Timeout))
			{
				$connection = true;
				break;
			}
			// printf("%s host could not connect<br>", $hosts[$x]); //debug only
			$x++;
		}
		if(!$connection)
			$this->error_handler("SMTP Error: could not connect to SMTP host server(s)");
	  	  
		$smtp->Hello($this->Helo);
		$smtp->Mail(sprintf("<%s>", $this->From));
		
		for($x = 0; $x < count($this->to); $x++)
			$smtp->Recipient(sprintf("<%s>", $this->to[$x][0]));
		for($x = 0; $x < count($this->cc); $x++)
			$smtp->Recipient(sprintf("<%s>", $this->cc[$x][0]));
		for($x = 0; $x < count($this->bcc); $x++)
			$smtp->Recipient(sprintf("<%s>", $this->bcc[$x][0]));

		$smtp->Data(sprintf("%s%s", $header, $body));
		$smtp->Quit();		
	}
	

	/////////////////////////////////////////////////
	// MESSAGE CREATION METHODS
	/////////////////////////////////////////////////
	
	// Creates recipient headers
	function addr_append($type, $addr) {
		$addr_str = "";
		$addr_str .= sprintf("%s: %s <%s>", $type, $addr[0][1], $addr[0][0]);
		if(count($addr) > 1)
		{
			for($x = 1; $x < count($addr); $x++)
			{
				$addr_str .= sprintf(", %s <%s>", $addr[$x][1], $addr[$x][0]);
			}
			$addr_str .= "\n";
		}
		else
			$addr_str .= "\n";
		
		return($addr_str);
	}
	
	// Wraps message for use with mailers that don't 
	// automatically perform wrapping
	// Written by philippe@cyberabuse.org
	function wordwrap($message, $length) {
		$line=explode("\n", $message);
		$message="";
		for ($i=0 ;$i < count($line); $i++) 
		{
			$line_part = explode(" ", trim($line[$i]));
			$buf = "";
			for ($e = 0; $e<count($line_part); $e++) 
			{
				$buf_o = $buf;
				if ($e == 0)
					$buf .= $line_part[$e];
				else 
					$buf .= " " . $line_part[$e];
				if (strlen($buf) > $length and $buf_o != "")
				{
					$message .= $buf_o . "\n";
					$buf = $line_part[$e];
				}
			}
			$message .= $buf . "\n";
		}
		return ($message);
	}
	
	// Assembles and returns the message header
	function create_header() {
		$header = array();
		$header[] = sprintf("From: %s <%s>\n", $this->FromName, trim($this->From));
		$header[] = $from;
		$header[] = $this->addr_append("To", $this->to);
		if(count($this->cc) > 0)
			$header[] = $this->addr_append("cc", $this->cc);
		if(count($this->bcc) > 0)
			$header[] = $this->addr_append("bcc", $this->bcc);
		if(count($this->ReplyTo) > 0)
			$header[] = $this->addr_append("Reply-to", $this->ReplyTo);
		$header[] = sprintf("Subject: %s\n", trim($this->Subject));
		$header[] = sprintf("Return-Path: %s\n", trim($this->From));
		$header[] = sprintf("X-Priority: %d\n", $this->Priority);
		$header[] = sprintf("X-Mailer: phpmailer [version .9]\n");
		$header[] = sprintf("Content-Transfer-Encoding: %s\n", $this->Encoding);
		// $header[] = sprintf("Content-Length: %d\n", (strlen($this->Body) * 7));
		if(count($this->attachment) > 0)
		{
			$header[] = sprintf("Content-Type: Multipart/Mixed; charset = \"%s\";\n", $this->CharSet);
			$header[] = sprintf(" boundary=\"Boundary-=%s\"\n", $this->boundary);
		}
		else
		{
			$header[] = sprintf("Content-Type: %s; charset = \"%s\";\n", $this->ContentType, $this->CharSet);
		}
		$header[] = "MIME-Version: 1.0\n\n";
		
		return(join("", $header));
	}

	// Assembles and returns the message body
	function create_body() {
		// wordwrap the message body if set
		if($this->WordWrap)
			$this->Body = $this->wordwrap($this->Body, $this->WordWrap);

		if(count($this->attachment) > 0)
			$body = $this->attach_all();
		else
			$body = $this->Body;
		
		return($body);		
	}
	
	
	/////////////////////////////////////////////////
	// ATTACHMENT METHODS
	/////////////////////////////////////////////////

	// Check if attachment is valid and add to list			
	function AddAttachment($path) {
		if(!is_file($path))
			$this->error_handler(sprintf("Could not find %s file on filesystem", $path));

		// Separate file name from full path
		$separator = "/";
		$len = strlen($path);
		
		// Set $separator to win32 style
		if(!ereg($separator, $path))
			$separator = "\\";
		
		// Get the filename from the path
		$pos = strrpos($path, $separator) + 1;
		$filename = substr($path, $pos, $len);
		
		// Set message boundary
		$this->boundary = "_b" . md5(uniqid(time()));

		// Append to $attachment array
		$cur = count($this->attachment);		
		$this->attachment[$cur][0] = $path;
		$this->attachment[$cur][1] = $filename;
	}

	// Attach text and binary attachments to body
	function attach_all() {
		// Return text of body
		$mime = array();
		$mime[] = sprintf("--Boundary-=%s\n", $this->boundary);
		$mime[] = sprintf("Content-Type: %s\n", $this->ContentType);
		$mime[] = "Content-Transfer-Encoding: 8bit\n";
		$mime[] = sprintf("%s\n\n", $this->Body);
		
		// Add all attachments
		for($x = 0; $x < count($this->attachment); $x++)
		{
			$path = $this->attachment[$x][0];
			$filename = $this->attachment[$x][1];
			$mime[] = sprintf("--Boundary-=%s\n", $this->boundary);
			$mime[] = "Content-Type: application/octet-stream;\n";
			$mime[] = sprintf("name=\"%s\"\n", $filename);
			$mime[] = "Content-Transfer-Encoding: base64\n";
			$mime[] = sprintf("Content-Disposition: attachment; filename=\"%s\"\n\n", $filename);
			$mime[] = sprintf("%s\n\n", $this->encode_file($path));
		}
		$mime[] = sprintf("\n--Boundary-=%s--\n", $this->boundary);
		
		return(join("", $mime));
	}
	
	// Encode attachment in base64 format
	function encode_file ($path) {
		if(!$fd = fopen($path, "r"))
			$this->error_handler("File Error: Could not open file %s", $path);
		$file = fread($fd, filesize($path));
		
		// chunk_split is found in PHP >= 3.0.6
		$encoded = chunk_split(base64_encode($file));
		fclose($fd);
		
		return($encoded);
	}
	
	/////////////////////////////////////////////////
	// MISCELLANEOUS METHODS
	/////////////////////////////////////////////////

	// Print out error and exit
	function error_handler($msg) {
		if($this->MailerDebug == true)
		{
			print("<h2>Mailer Error</h2>");
			print("Description:<br>");
			printf("<font color=\"FF0000\">%s</font>", $msg);
			//exit;
		}
	}
}

?>