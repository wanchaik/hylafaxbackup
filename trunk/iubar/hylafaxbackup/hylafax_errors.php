<?php

// http://hylafax.cvs.sourceforge.net/*checkout*/hylafax/hylafax/faxd/README.errorcodes


$errors = array();
$errors["E000-E049"] = "call failures";

$errors["E000"] = "Call successful";

$errors["E001"] = "Busy signal detected";
$errors["E002"] = "No carrier detected";
$errors["E003"] = "No answer from remote";
$errors["E004"] = "No local dialtone";
$errors["E005"] = "Invalid dialing command";
$errors["E006"] = "Unknown problem";
$errors["E007"] = "Carrier established, but Phase A failure";
$errors["E008"] = "Data connection established (wanted fax)";
$errors["E009"] = "Glare - RING detected";
$errors["E010"] = "Blacklisted by modem";
$errors["E011"] = "Ringback detected, no answer without CED";
$errors["E012"] = "Ring detected without successful handshake";

$errors["E050-E099"] = "non Class-specific fax protocol failures";

$errors["E050"] = "Missing EOL after 5 seconds";
$errors["E051"] = "Procedure interrupt received, job terminated";
$errors["E052"] = "Write error to TIFF file";

$errors["E100-E199"] = "Class 1-specific protocol failure";

$errors["E100"] = "Failure to receive silence (synchronization failure).";
$errors["E101"] = "Failure to raise V.21 transmission carrier.";
$errors["E102"] = "No sender protocol (T.30 T1 timeout)";
$errors["E103"] = "RSPREC error/got DCN (sender abort)";
$errors["E104"] = "RSPREC invalid response received";
$errors["E105"] = "Failure to train modems";
$errors["E106"] = "RSPREC error/got EOT";
$errors["E107"] = "Can not continue after DIS/DTC";
$errors["E108"] = "COMREC received DCN (sender abort)";
$errors["E109"] = "No response to RNR repeated 3 times.";
$errors["E110"] = "COMREC invalid response received";
$errors["E111"] = "V.21 signal reception timeout; expected page possibly not received in full";
$errors["E112"] = "Failed to properly detect high-speed data carrier.";
$errors["E113"] = "Received invalid CTC signal in V.34-Fax.";
$errors["E114"] = "Failed to properly open V.34 primary channel.";
$errors["E115"] = "Received premature V.34 termination.";
$errors["E116"] = "Failed to properly open V.34 control channel.";
$errors["E117"] = "COMREC invalid response to repeated PPR received";
$errors["E118"] = "T.30 T2 timeout, expected signal not received";
$errors["E119"] = "COMREC invalid partial-page signal received";
$errors["E120"] = "Cannot synchronize ECM frame reception.";
$errors["E121"] = "ECM page received containing no image data.";
$errors["E122"] = "Remote has no T.4 receiver capability";
$errors["E123"] = "DTC received when expecting DIS (not supported)";
$errors["E124"] = "COMREC error in transmit Phase B/got DCN";
$errors["E125"] = "COMREC invalid command received/no DIS or DTC";
$errors["E126"] = "No receiver protocol (T.30 T1 timeout)";
$errors["E127"] = "Stop and wait failure (modem on hook)";
$errors["E128"] = "Remote fax disconnected prematurely";
$errors["E129"] = "Procedure interrupt (operator intervention)";
$errors["E130"] = "Unable to transmit page (giving up after RTN)";
$errors["E131"] = "Unable to transmit page (giving up after 3 attempts)";
$errors["E132"] = "Unable to transmit page (NAK at all possible signalling rates)";
$errors["E133"] = "Unable to transmit page (NAK with operator intervention)";
$errors["E134"] = "Fax protocol error (unknown frame received)";
$errors["E135"] = "Fax protocol error (command repeated 3 times)";
$errors["E136"] = "DIS/DTC received 3 times; DCS not recognized";
$errors["E137"] = "Failure to train remote modem at 2400 bps or minimum speed";
$errors["E138"] = "Receiver flow control exceeded timer.";
$errors["E139"] = "No response to RR repeated 3 times.";
$errors["E140"] = "COMREC invalid response received to RR.";
$errors["E141"] = "No response to CTC repeated 3 times.";
$errors["E142"] = "COMREC invalid response received to CTC.";
$errors["E143"] = "Failure to transmit clean ECM image data.";
$errors["E144"] = "No response to EOR repeated 3 times.";
$errors["E145"] = "COMREC invalid response received to EOR.";
$errors["E146"] = "COMREC invalid response received to PPS.";
$errors["E147"] = "No response to PPS repeated 3 times.";
$errors["E148"] = "Unable to establish message carrier";
$errors["E149"] = "Unspecified Transmit Phase C error";
$errors["E150"] = "No response to MPS repeated 3 tries";
$errors["E151"] = "No response to EOP repeated 3 tries";
$errors["E152"] = "No response to EOM repeated 3 tries";
$errors["E153"] = "No response to PPM repeated 3 tries";
$errors["E154"] = "Timeout waiting for Phase C carrier drop.";
$errors["E155"] = "PPM received with no image data.  To continue risks receipt confirmation.";

$errors["E200-E299"] = "Class 2-specific protocol failure";

$errors["E200"] = "Unable to request polling operation (modem may not support polling)";
$errors["E201"] = "Unable to setup polling identifer (modem command failed)";
$errors["E202"] = "Unable to setup selective polling address (modem command failed)";
$errors["E203"] = "Unable to setup polling password (modem command failed)";
$errors["E204"] = "Unable to send password (modem command failed)";
$errors["E205"] = "Unable to send subaddress (modem command failed)";
$errors["E206"] = "Unable to restrict minimum transmit speed to %s (modem command failed)";
$errors["E207"] = "Unable to setup session parameters prior to call (modem command failed)";
$errors["E208"] = "Unable to set session parameters";
$errors["E209"] = "<no description>";
$errors["E210"] = "Unknown hangup code";
$errors["E211"] = "Normal and proper end of connection";
$errors["E212"] = "Ring detect without successful handshake";
$errors["E213"] = "Call aborted,  from +FK or <CAN>";
$errors["E214"] = "No loop current";
$errors["E215"] = "Ringback detected, no answer (timeout)";
$errors["E216"] = "Ringback detected, no answer without CED";
$errors["E217"] = "Unspecified Phase A error";
$errors["E218"] = "No answer (T.30 T1 timeout)";
$errors["E219"] = "Unspecified Transmit Phase B error";
$errors["E220"] = "Remote cannot be polled";
$errors["E221"] = "COMREC error in transmit Phase B/got DCN";
$errors["E222"] = "COMREC invalid command received/no DIS or DTC";
$errors["E223"] = "RSPREC error/got DCN";
$errors["E224"] = "DCS sent 3 times without response";
$errors["E225"] = "DIS/DTC received 3 times; DCS not recognized";
$errors["E226"] = "Failure to train at 2400 bps or +FMINSP value";
$errors["E227"] = "RSPREC invalid response received";
$errors["E228"] = "Unspecified Transmit Phase C error";
$errors["E229"] = "Unspecified Image format error";
$errors["E230"] = "Image conversion error";
$errors["E231"] = "DTE to DCE data underflow";
$errors["E232"] = "Unrecognized Transparent data command";
$errors["E233"] = "Image error, line length wrong";
$errors["E234"] = "Image error, page length wrong";
$errors["E235"] = "Image error, wrong compression code";
$errors["E236"] = "Unspecified Transmit Phase D error, including +FPHCTO timeout between data and +FET command";
$errors["E237"] = "RSPREC error/got DCN";
$errors["E238"] = "No response to MPS repeated 3 times";
$errors["E239"] = "Invalid response to MPS";
$errors["E240"] = "No response to EOP repeated 3 times";
$errors["E241"] = "Invalid response to EOP";
$errors["E242"] = "No response to EOM repeated 3 times";
$errors["E243"] = "Invalid response to EOM";
$errors["E244"] = "Unable to continue after PIN or PIP";
$errors["E245"] = "Unspecified Receive Phase B error";
$errors["E246"] = "RSPREC error/got DCN";
$errors["E247"] = "COMREC error";
$errors["E248"] = "T.30 T2 timeout, expected page not received";
$errors["E249"] = "T.30 T1 timeout after EOM received";
$errors["E250"] = "Unspecified Phase C error, including too much delay between TCF and +FDR command";
$errors["E251"] = "Missing EOL after 5 seconds (section 3.2/T.4)";
$errors["E252"] = "DCE to DTE buffer overflow";
$errors["E253"] = "Bad CRC or frame (ECM or BFT modes)";
$errors["E254"] = "Unspecified Phase D error";
$errors["E255"] = "RSPREC invalid response received";
$errors["E256"] = "COMREC invalid response received";
$errors["E257"] = "Unable to continue after PIN or PIP, no PRI-Q";
$errors["E258"] = "Command or signal 10 sec. timeout";
$errors["E259"] = "Cannot send: +FMINSP > remote's +FDIS(BR) code";
$errors["E260"] = "Cannot send: remote is V.29 only, local DCE constrained to 2400 or 4800 bps";
$errors["E261"] = "Remote station cannot receive (DIS bit 10)";
$errors["E262"] = "+FK aborted or <CAN> aborted";
$errors["E263"] = "+Format conversion error in +FDT=DF,VR, WD,LN Incompatible and inconvertable data format";
$errors["E264"] = "Remote cannot receive";
$errors["E265"] = "After +FDR, DCE waited more than 30 seconds for XON from DTE after XOFF from DTE";
$errors["E266"] = "In Polling Phase B, remote cannot be polled";

$errors["E267-279"] = "(currently unused)";

$errors["E280"] = "Procedure interrupt (operator intervention)";
$errors["E281"] = "Unable to transmit page (giving up after RTN)";
$errors["E282"] = "Unable to transmit page (giving up after 3 attempts)";
$errors["E283"] = "Unable to transmit page (NAK at all possible signalling rates)";
$errors["E284"] = "Unable to transmit page (NAK with operator intervention)";
$errors["E285"] = "Modem protocol error (unknown post-page response)";
$errors["E286"] = "Batching protocol error";
$errors["E287"] = "Communication failure during Phase B/C";
$errors["E288"] = "Communication failure during Phase B/C (modem protocol botch)";

$errors["E300-E399"] = "Non-T.30 client or server failure";

$errors["E301"] = "Receive aborted due to operator intervention";
$errors["E302"] = "Problem reading document directory";
$errors["E303"] = "Internal botch; %s post-page handling string"; // "Internal botch; %s post-page handling string \"%s\"";
$errors["E304"] = "Maximum receive page count exceeded, call terminated";
$errors["E305"] = "Could not fork for scripted configuration.";
$errors["E306"] = "Bad exit status %#o for '%s'";
$errors["E307"] = "Could not open a pipe for scripted configuration.";
$errors["E308"] = "ANSWER: CALL REJECTED";
$errors["E309"] = "ANSWER: Call deduced as %s, but told to answer as %s; call ignored";
$errors["E310"] = "External getty use is not permitted {E310}";
$errors["E311"] = "%s: could not create";
$errors["E312"] = "%s: can not fork: %s";
$errors["E313"] = "ERROR: Unknown status";
$errors["E314"] = "Can not open document file %s";
$errors["E315"] = "Can not set directory %u in document file %s";
$errors["E316"] = "Error reading directory %u in document file %s";
$errors["E317"] = "Too many pages in submission; max %u";
$errors["E318"] = "Unable to lock shared document file";
$errors["E319"] = "Unable to open shared document file";
$errors["E320"] = "Unable to create document file";
$errors["E321"] = "Converted document is not valid TIFF";
$errors["E322"] = "Could not reopen converted document to verify format";
$errors["E323"] = "Job contains no documents";
$errors["E324"] = "Modem does not support polling";
$errors["E325"] = "Kill time expired";
$errors["E326"] = "Invalid or corrupted job description file";
$errors["E327"] = "REJECT: Unable to convert dial string to canonical format";
$errors["E328"] = "REJECT: Requested modem %s is not registered";
$errors["E329"] = "REJECT: No work found in job file";
$errors["E330"] = "REJECT: Page width (%u) appears invalid";
$errors["E331"] = "REJECT: Job expiration time (%u) appears invalid";
$errors["E332"] = "REJECT: Time-to-send (%u) appears invalid";
$errors["E333"] = "REJECT: Too many attempts to dial";
$errors["E334"] = "REJECT: Too many attempts to transmit: %u, max %u";
$errors["E335"] = "REJECT: Too many pages in submission: %u, max %u";
$errors["E336"] = "REJECT: Modem is configured as exempt from accepting jobs";
$errors["E337"] = "Blocked by concurrent calls";
$errors["E338"] = "Delayed by time-of-day restrictions";
$errors["E339"] = "Delayed by outbound call staggering";
$errors["E340"] = "Could not fork to prepare job for transmission";
$errors["E341"] = "Could not fork to start job transmission";
$errors["E342"] = "Delayed by prior call";
$errors["E343"] = "Send program terminated abnormally; unable to exec %s";
$errors["E344"] = "Job interrupted by user";
$errors["E345"] = "Job aborted by request";

$errors["E400-E499"] = "job/modem incompatibility";

$errors["E400"] = "Modem does not support negotiated signalling rate";
$errors["E401"] = "Modem does not support negotiated min scanline time";
$errors["E402"] = "Document is not in a Group 3 or Group 4 compatible format (compression %u)";
$errors["E403"] = "Document was encoded with 2DMMR, but client does not support this data format";
$errors["E404"] = "Document was encoded with 2DMMR, but modem does not support this data format";
$errors["E405"] = "Document was encoded with 2DMMR, but ECM is not being used.";
$errors["E406"] = "Document was encoded with 2DMR, but client does not support this data format";
$errors["E407"] = "Document was encoded with 2DMR, but modem does not support this data format";
$errors["E408"] = "Hyperfine resolution document is not supported by client, image resolution %g x %g lines/mm";
$errors["E409"] = "Hyperfine resolution document is not supported by modem, image resolution %g x %g lines/mm";
$errors["E410"] = "Superfine resolution document is not supported by client, image resolution %g lines/mm";
$errors["E411"] = "Superfine resolution document is not supported by modem, image resolution %g lines/mm";
$errors["E412"] = "300x300 resolution document is not supported by client, image resolution %g lines/mm";
$errors["E413"] = "300x300 resolution document is not supported by modem, image resolution %g lines/mm";
$errors["E414"] = "High resolution document is not supported by client, image resolution %g lines/mm";
$errors["E415"] = "High resolution document is not supported by modem, image resolution %g lines/mm";
$errors["E416"] = "Client does not support document page width, max remote page width %g pixels, image width %lu pixels";
$errors["E417"] = "Modem does not support document page width, max page width %g pixels, image width %lu pixels";
$errors["E418"] = "Client does not support document page length, max remote page length %d mm, image length %lu rows (%.2f mm)";
$errors["E419"] = "Modem does not support document page length, max page length %s mm, image length %lu rows (%.2f mm)";

$errors["E500-E599"] = "paging failures";

$errors["E500"] = "No initial ID response from paging central";
$errors["E501"] = "Login failed multiple times";
$errors["E502"] = "Protocol failure: %s from paging central";
$errors["E503"] = "Protocol failure: %s waiting for go-ahead message";
$errors["E504"] = "Message block not acknowledged by paging central after multiple tries";
$errors["E505"] = "Message block transmit failed paging central rejected it";
$errors["E506"] = "Protocol failure: paging central responded to message block transmit with forced disconnect";
$errors["E507"] = "Protocol failure: %s to message block transmit";
$errors["E508"] = "Paging central rejected content; check PIN";
$errors["E509"] = "Protocol failure: timeout waiting for transaction ACK/NAK from paging central";


function getErrorDesc($key){
	global $errors;
	$desc = "<no description>";
	if(isset($errors["$key"])){
		$desc = substr($errors["$key"], 0, 25);
	}
	return $desc;
}


?>
