<?php
/*
 * Usage:
 *
 *  $locale=al2gt(<array of supported languages/charsets in gettext syntax>,
 *                <MIME type of document>);
 *  setlocale('LC_ALL', $locale); // or 'LC_MESSAGES', or whatever...
 *
 * Example:
 *
 *  $langs=array('nl_BE.ISO-8859-15','nl_BE.UTF-8','en_US.UTF-8','en_GB.UTF-8');
 *  $locale=al2gt($langs, 'text/html');
 *  setlocale('LC_ALL', $locale);
 *
 * Note that this will send out header information (to be
 * RFC2616-compliant), so it must be called before anything is sent to
 * the user.
 * 
 * Assumptions made:
 * * Charset encodings are written the same way as the Accept-Charset
 *   HTTP header specifies them (RFC2616), except that they're parsed
 *   case-insensitive.
 * * Country codes and language codes are the same in both gettext and
 *   the Accept-Language syntax (except for the case differences, which
 *   are dealt with easily). If not, some input may be ignored.
 * * The provided gettext-strings are fully qualified; i.e., no "en_US";
 *   always "en_US.ISO-8859-15" or "en_US.UTF-8", or whichever has been
 *   used. "en.ISO-8859-15" is OK, though.
 * * The language is more important than the charset; i.e., if the
 *   following is given:
 * 
 *   Accept-Language: nl-be, nl;q=0.8, en-us;q=0.5, en;q=0.3
 *
 */

class ezxISO936
{
    const ISO936_1 = 0;
    const ISO936_2 = 1;
    public $languages = array
    (
    #   array( array( 'ISO 639-1' ), array( 'ISO 639-2' ) ),
    # oder
    #   array( array( 'ISO 639-1' ), array( 'ISO 639-2', 'ISO 639-2-ALTERNATIV2', 'ISO 639-2-ALTERNATIV3' ) ),
        array( array( 'ab' ), array( 'abk' ) ),
    	array( array( '' ), array( 'ace' ) ),
    	array( array( '' ), array( 'ach' ) ),
    	array( array( '' ), array( 'ada' ) ),
    	array( array( '' ), array( 'ady' ) ),
    	array( array( '' ), array( 'ady' ) ),
    	array( array( 'aa' ), array( 'aar' ) ),
    	array( array( '' ), array( 'afh' ) ),
    	array( array( '' ), array( 'afr' ) ),
    	array( array( '' ), array( 'afa' ) ),
    	array( array( '' ), array( 'ain' ) ),
    	array( array( 'ak' ), array( 'aka' ) ),
    	array( array( '' ), array( 'akk' ) ),
    	array( array( 'sq' ), array( 'alb', 'sqi' ) ),
    	array( array( '' ), array( 'gsw' ) ),
    	array( array( '' ), array( 'ale' ) ),
    	array( array( '' ), array( 'alg' ) ),
    	array( array( '' ), array( 'tut' ) ),
    	array( array( 'am' ), array( 'amh' ) ),
    	array( array( '' ), array( 'anp' ) ),
    	array( array( '' ), array( 'apa' ) ),
    	array( array( 'ar' ), array( 'ara' ) ),
    	array( array( 'an' ), array( 'arg' ) ),
    	array( array( '' ), array( 'arc' ) ),
    	array( array( '' ), array( 'arp' ) ),
    	array( array( '' ), array( 'arn' ) ),
    	array( array( '' ), array( 'arw' ) ),
    	array( array( 'hy' ), array( 'arm', 'hye' ) ),
    	array( array( '' ), array( 'rup' ) ),
    	array( array( 'as' ), array( 'asm' ) ),
    	array( array( '' ), array( 'ast' ) ),
    	array( array( '' ), array( 'ath' ) ),
    	array( array( '' ), array( 'aus' ) ),
    	array( array( '' ), array( 'map' ) ),
    	array( array( 'av' ), array( 'ava' ) ),
    	array( array( 'ae' ), array( 'ave' ) ),
    	array( array( '' ), array( 'awa' ) ),
    	array( array( 'ay' ), array( 'aym' ) ),
    	array( array( 'az' ), array( 'aze' ) ),
    	array( array( '' ), array( 'ast' ) ),
    	array( array( '' ), array( 'ban' ) ),
    	array( array( '' ), array( 'bat' ) ),
    	array( array( '' ), array( 'bal' ) ),
    	array( array( 'bm' ), array( 'bam' ) ),
    	array( array( '' ), array( 'bai' ) ),
    	array( array( '' ), array( 'bad' ) ),
    	array( array( '' ), array( 'bnt' ) ),
    	array( array( '' ), array( 'bas' ) ),
    	array( array( 'ba' ), array( 'bak' ) ),
    	array( array( 'eu' ), array( 'baq', 'eus' ) ),
    	array( array( '' ), array( 'btk' ) ),
    	array( array( '' ), array( 'bej' ) ),
    	array( array( 'be' ), array( 'bel' ) ),
    	array( array( '' ), array( 'bem' ) ),
    	array( array( 'bn' ), array( 'ben' ) ),
    	array( array( '' ), array( 'ber' ) ),
    	array( array( '' ), array( 'bho' ) ),
    	array( array( 'bh' ), array( 'bih' ) ),
    	array( array( '' ), array( 'spa' ) ),
    	array( array( '' ), array( 'bik' ) ),
    	array( array( '' ), array( 'byn' ) ),
    	array( array( 'nb' ), array( 'nob' ) ),
    	array( array( 'bs' ), array( 'bos' ) ),
    	array( array( '' ), array( 'bra' ) ),
    	array( array( 'br' ), array( 'bre' ) ),
    	array( array( '' ), array( 'bug' ) ),
    	array( array( 'bg' ), array( 'bul' ) ),
    	array( array( 'es' ), array( 'spa' ) ),
    	array( array( '' ), array( 'bua' ) ),
    	array( array( 'es' ), array( 'spa' ) ),
    	array( array( 'my' ), array( 'bur', 'mya' ) ),
	   	array( array( '' ), array( 'cad' ) ),
    	array( array( '' ), array( 'car' ) ),
    	array( array( 'es' ), array( 'spa' ) ),
    	array( array( 'ca' ), array( 'cat' ) ),
    	array( array( '' ), array( 'cau' ) ),
    	array( array( '' ), array( 'ceb' ) ),
    	array( array( '' ), array( 'cel' ) ),
    	array( array( '' ), array( 'cai' ) ),
    	array( array( '' ), array( 'chg' ) ),
    	array( array( '' ), array( 'cmc' ) ),
    	array( array( 'ch' ), array( 'cha' ) ),
    	array( array( 'ce' ), array( 'che' ) ),
    	array( array( '' ), array( 'chr' ) ),
    	array( array( 'ny' ), array( 'nya' ) ),
    	array( array( '' ), array( 'spa' ) ),
    	array( array( '' ), array( 'chy' ) ),
    	array( array( '' ), array( 'chb' ) ),
    	array( array( 'ny' ), array( 'nya' ) ),
    	array( array( 'zh' ), array( 'chi', 'zho' ) ),
    	array( array( '' ), array( 'chn' ) ),
    	array( array( '' ), array( 'chp' ) ),
    	array( array( '' ), array( 'cho' ) ),
    	array( array( 'za' ), array( 'zha' ) ),
    	array( array( 'cu' ), array( 'chu' ) ),
    	array( array( 'cu' ), array( 'chu' ) ),
    	array( array( '' ), array( 'chk' ) ),
    	array( array( 'cv' ), array( 'chv' ) ),
    	array( array( '' ), array( 'nwc' ) ),
    	array( array( '' ), array( 'nwc' ) ),
    	array( array( '' ), array( 'cop' ) ),
    	array( array( 'kw' ), array( 'cor' ) ),
    	array( array( 'co' ), array( 'cos' ) ),
    	array( array( 'ce' ), array( 'cre' ) ),
    	array( array( '' ), array( 'mus' ) ),
    	array( array( '' ), array( 'crp' ) ),
    	array( array( '' ), array( 'cpe' ) ),
    	array( array( '' ), array( 'cpf' ) ),
    	array( array( '' ), array( 'cpp' ) ),
    	array( array( '' ), array( 'crh' ) ),
    	array( array( '' ), array( 'crh' ) ),
    	array( array( 'hr' ), array( 'scr', 'hrv' ) ),
    	array( array( '' ), array( 'cus' ) ),
    	array( array( 'cs' ), array( 'cze', 'ces' ) ),
    	array( array( '' ), array( 'dak' ) ),
    	array( array( 'da' ), array( 'dan' ) ),
    	array( array( '' ), array( 'dar' ) ),
    	array( array( '' ), array( 'day' ) ),
    	array( array( '' ), array( 'del' ) ),
    	array( array( 'dv' ), array( 'div' ) ),
    	array( array( '' ), array( 'din' ) ),
    	array( array( 'dv' ), array( 'div' ) ),
    	array( array( '' ), array( 'doi' ) ),
    	array( array( '' ), array( 'dgr' ) ),
    	array( array( '' ), array( 'dra' ) ),
    	array( array( '' ), array( 'dua' ) ),
    	array( array( 'nl' ), array( 'dut', 'nld' ) ),
    	array( array( '' ), array( 'dum' ) ),
    	array( array( '' ), array( 'dyn' ) ),
    	array( array( 'dz' ), array( 'dzo' ) ),
       	array( array( '' ), array( 'frs' ) ),
    	array( array( '' ), array( 'efi' ) ),
    	array( array( '' ), array( 'egy' ) ),
    	array( array( '' ), array( 'eka' ) ),
    	array( array( '' ), array( 'elx' ) ),
    	array( array( 'en' ), array( 'eng' ) ),
    	array( array( '' ), array( 'enm' ) ),
    	array( array( '' ), array( 'ang' ) ),
    	array( array( '' ), array( 'myv' ) ),
    	array( array( 'eo' ), array( 'epo' ) ),
    	array( array( 'et' ), array( 'est' ) ),
    	array( array( 'ee' ), array( 'ewe' ) ),
    	array( array( '' ), array( 'ewo' ) ),
    	array( array( '' ), array( 'fan' ) ),
    	array( array( '' ), array( 'fat' ) ),
    	array( array( 'fo' ), array( 'fao' ) ),
    	array( array( 'fj' ), array( 'fij' ) ),
    	array( array( '' ), array( 'fil' ) ),
    	array( array( 'fi' ), array( 'fin' ) ),
    	array( array( '' ), array( 'fiu' ) ),
    	array( array( 'nl' ), array( 'dut', 'nld' ) ),
    	array( array( '' ), array( 'fon' ) ),
    	array( array( 'fr' ), array( 'fre', 'fra' ) ),
    	array( array( '' ), array( 'frm' ) ),
    	array( array( '' ), array( 'fro' ) ),
    	array( array( '' ), array( 'fur' ) ),
    	array( array( 'ff' ), array( 'ful' ) ),
       	array( array( '' ), array( 'gaa' ) ),
    	array( array( 'gd' ), array( 'gla' ) ),
    	array( array( 'gl' ), array( 'glg' ) ),
    	array( array( 'lg' ), array( 'lug' ) ),
    	array( array( '' ), array( 'gay' ) ),
    	array( array( '' ), array( 'gba' ) ),
    	array( array( '' ), array( 'gez' ) ),
    	array( array( 'ka' ), array( 'geo', 'kat' ) ),
    	array( array( 'de' ), array( 'ger', 'deu' ) ),
    	array( array( '' ), array( 'nds' ) ),
    	array( array( '' ), array( 'gmh' ) ),
    	array( array( '' ), array( 'goh' ) ),
    	array( array( '' ), array( 'gem' ) ),
    	array( array( 'ki' ), array( 'kik' ) ),
    	array( array( '' ), array( 'gil' ) ),
    	array( array( '' ), array( 'gon' ) ),
    	array( array( '' ), array( 'gor' ) ),
    	array( array( '' ), array( 'got' ) ),
    	array( array( '' ), array( 'grb' ) ),
    	array( array( '' ), array( 'grc' ) ),
    	array( array( 'el' ), array( 'gre', 'ell' ) ),
    	array( array( 'kl' ), array( 'kal' ) ),
    	array( array( 'gn' ), array( 'grn' ) ),
    	array( array( 'gu' ), array( 'guj' ) ),
    	array( array( '' ), array( 'gwi' ) ),
    	array( array( '' ), array( 'hai' ) ),
    	array( array( 'ht' ), array( 'hat' ) ),
    	array( array( 'ht' ), array( 'hat' ) ),
    	array( array( 'ha' ), array( 'hau' ) ),
    	array( array( '' ), array( 'haw' ) ),
    	array( array( 'he' ), array( 'heb' ) ),
    	array( array( 'hz' ), array( 'her' ) ),
    	array( array( '' ), array( 'hil' ) ),
    	array( array( '' ), array( 'him' ) ),
    	array( array( 'hi' ), array( 'hin' ) ),
    	array( array( 'ho' ), array( 'hmo' ) ),
    	array( array( '' ), array( 'hit' ) ),
    	array( array( '' ), array( 'hmn' ) ),
    	array( array( 'hu' ), array( 'hun' ) ),
    	array( array( '' ), array( 'hup' ) ),
       	array( array( '' ), array( 'iba' ) ),
    	array( array( 'is' ), array( 'ice', 'isl' ) ),
    	array( array( 'io' ), array( 'ido' ) ),
    	array( array( 'ig' ), array( 'ibo' ) ),
    	array( array( '' ), array( 'ijo' ) ),
    	array( array( '' ), array( 'ilo' ) ),
    	array( array( '' ), array( 'smn' ) ),
    	array( array( '' ), array( 'inc' ) ),
    	array( array( '' ), array( 'ine' ) ),
    	array( array( 'id' ), array( 'ind' ) ),
    	array( array( '' ), array( 'inh' ) ),
    	array( array( 'ia' ), array( 'ina' ) ),
    	array( array( 'ie' ), array( 'ile' ) ),
    	array( array( 'iu' ), array( 'iku' ) ),
    	array( array( 'ik' ), array( 'ipk' ) ),
    	array( array( '' ), array( 'ira' ) ),
    	array( array( 'ga' ), array( 'gle' ) ),
    	array( array( '' ), array( 'mga' ) ),
    	array( array( '' ), array( 'sga' ) ),
    	array( array( '' ), array( 'iro' ) ),
    	array( array( 'it' ), array( 'ita' ) ),
    	array( array( 'ja' ), array( 'jpn' ) ),
    	array( array( 'jv' ), array( 'jav' ) ),
    	array( array( '' ), array( 'jrb' ) ),
    	array( array( '' ), array( 'jpr' ) ),
       	array( array( '' ), array( 'kbd' ) ),
    	array( array( '' ), array( 'kab' ) ),
    	array( array( '' ), array( 'kac' ) ),
    	array( array( 'kl' ), array( 'kal' ) ),
    	array( array( '' ), array( 'xal' ) ),
    	array( array( '' ), array( 'kam' ) ),
    	array( array( 'kn' ), array( 'kan' ) ),
    	array( array( 'kr' ), array( 'kau' ) ),
    	array( array( '' ), array( 'krc' ) ),
    	array( array( '' ), array( 'kaa' ) ),
    	array( array( '' ), array( 'krl' ) ),
    	array( array( '' ), array( 'kar' ) ),
    	array( array( 'ks' ), array( 'kas' ) ),
    	array( array( '' ), array( 'csb' ) ),
    	array( array( '' ), array( 'kaw' ) ),
    	array( array( 'kk' ), array( 'kaz' ) ),
    	array( array( '' ), array( 'kha' ) ),
    	array( array( 'km' ), array( 'khm' ) ),
    	array( array( '' ), array( 'khi' ) ),
    	array( array( '' ), array( 'kho' ) ),
    	array( array( 'ki' ), array( 'kik' ) ),
    	array( array( '' ), array( 'kmb' ) ),
    	array( array( 'rw' ), array( 'kin' ) ),
    	array( array( 'ky' ), array( 'kir' ) ),
    	array( array( '' ), array( 'tlh' ) ),
    	array( array( 'kv' ), array( 'kom' ) ),
    	array( array( 'kg' ), array( 'kon' ) ),
    	array( array( '' ), array( 'kok' ) ),
    	array( array( 'ko' ), array( 'kor' ) ),
    	array( array( '' ), array( 'kos' ) ),
    	array( array( '' ), array( 'kpe' ) ),
    	array( array( '' ), array( 'kro' ) ),
    	array( array( 'kj' ), array( 'kua' ) ),
    	array( array( '' ), array( 'kum' ) ),
    	array( array( 'ku' ), array( 'kru' ) ),
    	array( array( '' ), array( 'kut' ) ),
    	array( array( 'kj' ), array( 'kua' ) ),
    	array( array( '' ), array( 'lad' ) ),
    	array( array( '' ), array( 'lah' ) ),
    	array( array( '' ), array( 'lam' ) ),
    	array( array( 'lo' ), array( 'lao' ) ),
    	array( array( 'la' ), array( 'lat' ) ),
    	array( array( 'lv' ), array( 'lav' ) ),
    	array( array( 'lb' ), array( 'ltz' ) ),
    	array( array( '' ), array( 'lez' ) ),
    	array( array( 'li' ), array( 'lim' ) ),
    	array( array( 'li' ), array( 'lim' ) ),
    	array( array( 'li' ), array( 'lim' ) ),
    	array( array( 'ln' ), array( 'lin' ) ),
    	array( array( 'lt' ), array( 'lit' ) ),
    	array( array( '' ), array( 'jbo' ) ),
    	array( array( '' ), array( 'nds' ) ),
    	array( array( '' ), array( 'nds' ) ),
    	array( array( '' ), array( 'dsb' ) ),
    	array( array( '' ), array( 'loz' ) ),
    	array( array( 'lu' ), array( 'lub' ) ),
    	array( array( '' ), array( 'lua' ) ),
    	array( array( '' ), array( 'lui' ) ),
    	array( array( '' ), array( 'smj' ) ),
    	array( array( '' ), array( 'lun' ) ),
    	array( array( '' ), array( 'luo' ) ),
    	array( array( '' ), array( 'lus' ) ),
    	array( array( 'lb' ), array( 'ltz' ) ),
       	array( array( '' ), array( 'rup' ) ),
    	array( array( 'mk' ), array( 'mac', 'mkd' ) ),
    	array( array( '' ), array( 'mad' ) ),
    	array( array( '' ), array( 'mag' ) ),
    	array( array( '' ), array( 'mai' ) ),
    	array( array( '' ), array( 'mak' ) ),
    	array( array( 'mg' ), array( 'mlg' ) ),
    	array( array( 'ms' ), array( 'may', 'msa' ) ),
    	array( array( 'ml' ), array( 'mal' ) ),
    	array( array( 'dv' ), array( 'div' ) ),
    	array( array( 'mt' ), array( 'mlt' ) ),
    	array( array( '' ), array( 'mnc' ) ),
    	array( array( '' ), array( 'mdr' ) ),
    	array( array( '' ), array( 'man' ) ),
    	array( array( '' ), array( 'mni' ) ),
    	array( array( '' ), array( 'mno' ) ),
    	array( array( 'gv' ), array( 'glv' ) ),
    	array( array( 'mi' ), array( 'mao', 'mri' ) ),
    	array( array( 'mr' ), array( 'mar' ) ),
    	array( array( '' ), array( 'chm' ) ),
    	array( array( 'mh' ), array( 'mah' ) ),
    	array( array( '' ), array( 'mur' ) ),
    	array( array( '' ), array( 'mas' ) ),
    	array( array( '' ), array( 'myn' ) ),
    	array( array( '' ), array( 'men' ) ),
    	array( array( '' ), array( 'mic' ) ),
    	array( array( '' ), array( 'mic' ) ),
    	array( array( '' ), array( 'min' ) ),
    	array( array( '' ), array( 'mwl' ) ),
    	array( array( '' ), array( 'mis' ) ),
    	array( array( '' ), array( 'moh' ) ),
    	array( array( '' ), array( 'mdf' ) ),
    	array( array( 'mo' ), array( 'mol' ) ),
    	array( array( '' ), array( 'mkh' ) ),
    	array( array( '' ), array( 'lol' ) ),
    	array( array( 'mn' ), array( 'mon' ) ),
    	array( array( '' ), array( 'mos' ) ),
    	array( array( '' ), array( 'mul' ) ),
    	array( array( '' ), array( 'mun' ) ),
    	array( array( '' ), array( 'nah' ) ),
    	array( array( 'na' ), array( 'nau' ) ),
    	array( array( 'nv' ), array( 'nav' ) ),
    	array( array( 'nv' ), array( 'nav' ) ),
    	array( array( 'nd' ), array( 'nde' ) ),
    	array( array( 'nr' ), array( 'nbl' ) ),
    	array( array( 'ng' ), array( 'ndo' ) ),
    	array( array( '' ), array( 'nap' ) ),
    	array( array( '' ), array( 'new' ) ),
    	array( array( 'ne' ), array( 'nep' ) ),
    	array( array( '' ), array( 'new' ) ),
    	array( array( '' ), array( 'nia' ) ),
    	array( array( '' ), array( 'nic' ) ),
    	array( array( '' ), array( 'ssa' ) ),
    	array( array( '' ), array( 'niu' ) ),
    	array( array( '' ), array( 'nqo' ) ),
    	array( array( '' ), array( 'zxx' ) ),
    	array( array( '' ), array( 'nog' ) ),
    	array( array( '' ), array( 'non' ) ),
    	array( array( '' ), array( 'nai' ) ),
    	array( array( '' ), array( 'frr' ) ),
    	array( array( 'se' ), array( 'sme' ) ),
    	array( array( '' ), array( 'nso' ) ),
    	array( array( 'nd' ), array( 'nde' ) ),
    	array( array( 'no' ), array( 'nor' ) ),
    	array( array( 'nb' ), array( 'nob' ) ),
    	array( array( 'nn' ), array( 'nno' ) ),
    	array( array( '' ), array( 'nub' ) ),
    	array( array( '' ), array( 'nym' ) ),
    	array( array( 'ny' ), array( 'nya' ) ),
    	array( array( '' ), array( 'nyn' ) ),
    	array( array( 'nn' ), array( 'nno' ) ),
    	array( array( '' ), array( 'nyo' ) ),
    	array( array( '' ), array( 'nzi' ) ),
       	array( array( 'oc' ), array( 'oci' ) ),
    	array( array( '' ), array( 'xal' ) ),
    	array( array( 'oj' ), array( 'oji' ) ),
    	array( array( 'cu' ), array( 'chu' ) ),
    	array( array( 'or' ), array( 'ori' ) ),
    	array( array( 'om' ), array( 'orm' ) ),
    	array( array( '' ), array( 'osa' ) ),
    	array( array( 'os' ), array( 'oss' ) ),
    	array( array( 'os' ), array( 'oss' ) ),
    	array( array( '' ), array( 'oto' ) ),
    	array( array( '' ), array( 'pal' ) ),
    	array( array( '' ), array( 'pau' ) ),
    	array( array( '' ), array( 'pli' ) ),
    	array( array( '' ), array( 'pam' ) ),
    	array( array( '' ), array( 'pag' ) ),
    	array( array( 'pa' ), array( 'pan' ) ),
    	array( array( '' ), array( 'pap' ) ),
    	array( array( '' ), array( 'paa' ) ),
    	array( array( '' ), array( 'nso' ) ),
    	array( array( 'fa' ), array( 'per', 'fas' ) ),
    	array( array( '' ), array( 'peo' ) ),
    	array( array( '' ), array( 'phi' ) ),
    	array( array( '' ), array( 'phn' ) ),
    	array( array( '' ), array( 'fil' ) ),
    	array( array( '' ), array( 'pon' ) ),
    	array( array( 'pl' ), array( 'pol' ) ),
    	array( array( 'pt' ), array( 'por' ) ),
    	array( array( '' ), array( 'pra' ) ),
    	array( array( 'oc' ), array( 'oci' ) ),
    	array( array( '' ), array( 'pro' ) ),
    	array( array( 'pa' ), array( 'pan' ) ),
    	array( array( 'ps' ), array( 'pus' ) ),
      	array( array( 'qu' ), array( 'que' ) ),
    	array( array( 'rm' ), array( 'roh' ) ),
    	array( array( '' ), array( 'raj' ) ),
    	array( array( '' ), array( 'rap' ) ),
    	array( array( '' ), array( 'rar' ) ),
    	array( array( '' ), array( 'qaa', 'qtz' ) ),
    	array( array( '' ), array( 'roa' ) ),
    	array( array( 'ro' ), array( 'rum', 'ron' ) ),
    	array( array( '' ), array( 'rom' ) ),
    	array( array( 'rn' ), array( 'run' ) ),
    	array( array( 'ru' ), array( 'rus' ) ),
    	array( array( '' ), array( 'sal' ) ),
    	array( array( '' ), array( 'sam' ) ),
    	array( array( '' ), array( 'smi' ) ),
    	array( array( 'sm' ), array( 'smo' ) ),
    	array( array( '' ), array( 'sad' ) ),
    	array( array( 'sg' ), array( 'sag' ) ),
    	array( array( 'sa' ), array( 'san' ) ),
    	array( array( '' ), array( 'sat' ) ),
    	array( array( 'sc' ), array( 'srd' ) ),
    	array( array( '' ), array( 'sas' ) ),
    	array( array( '' ), array( 'nds' ) ),
    	array( array( '' ), array( 'spa' ) ),
    	array( array( '' ), array( 'sco' ) ),
    	array( array( 'gd' ), array( 'gla' ) ),
    	array( array( '' ), array( 'sel' ) ),
    	array( array( '' ), array( 'sem' ) ),
    	array( array( '' ), array( 'nso' ) ),
    	array( array( 'sr' ), array( 'scc', 'srp' ) ),
    	array( array( '' ), array( 'srr' ) ),
    	array( array( '' ), array( 'shn' ) ),
    	array( array( 'sn' ), array( 'sna' ) ),
    	array( array( 'ii' ), array( 'iii' ) ),
    	array( array( '' ), array( 'scn' ) ),
    	array( array( '' ), array( 'sid' ) ),
    	array( array( '' ), array( 'sgn' ) ),
    	array( array( '' ), array( 'bla' ) ),
    	array( array( 'sd' ), array( 'snd' ) ),
    	array( array( 'si' ), array( 'sin' ) ),
    	array( array( 'si' ), array( 'sin' ) ),
    	array( array( '' ), array( 'sit' ) ),
    	array( array( '' ), array( 'sio' ) ),
    	array( array( '' ), array( 'sms' ) ),
    	array( array( '' ), array( 'den' ) ),
    	array( array( '' ), array( 'sla' ) ),
    	array( array( 'sk' ), array( 'slo', 'slk' ) ),
    	array( array( 'sl' ), array( 'slv' ) ),
    	array( array( '' ), array( 'sog' ) ),
    	array( array( 'so' ), array( 'som' ) ),
    	array( array( '' ), array( 'son' ) ),
    	array( array( '' ), array( 'snk' ) ),
    	array( array( '' ), array( 'wen' ) ),
    	array( array( '' ), array( 'wen' ) ),
    	array( array( '' ), array( 'nso' ) ),
    	array( array( 'st' ), array( 'sot' ) ),
    	array( array( '' ), array( 'sai' ) ),
    	array( array( '' ), array( 'alt' ) ),
    	array( array( '' ), array( 'sma' ) ),
    	array( array( 'nr' ), array( 'nbl' ) ),
    	array( array( 'es' ), array( 'spa' ) ),
    	array( array( '' ), array( 'spa' ) ),
    	array( array( '' ), array( 'srn' ) ),
    	array( array( '' ), array( 'suk' ) ),
    	array( array( '' ), array( 'sux' ) ),
    	array( array( '' ), array( 'sun' ) ),
    	array( array( '' ), array( 'sus' ) ),
    	array( array( 'sw' ), array( 'swa' ) ),
    	array( array( 'ss' ), array( 'ssw' ) ),
    	array( array( 'sv' ), array( 'swe' ) ),
    	array( array( '' ), array( 'gsv' ) ),
    	array( array( '' ), array( 'syr' ) ),
    	array( array( 'tl' ), array( 'tgl' ) ),
    	array( array( 'ty' ), array( 'tah' ) ),
    	array( array( '' ), array( 'tai' ) ),
    	array( array( 'tg' ), array( 'tgk' ) ),
    	array( array( '' ), array( 'tmh' ) ),
    	array( array( 'ta' ), array( 'tam' ) ),
    	array( array( 'tt' ), array( 'tat' ) ),
    	array( array( 'te' ), array( 'tel' ) ),
    	array( array( '' ), array( 'ter' ) ),
    	array( array( '' ), array( 'tet' ) ),
    	array( array( 'th' ), array( 'tha' ) ),
    	array( array( 'bo' ), array( 'tib', 'bod' ) ),
    	array( array( '' ), array( 'tig' ) ),
    	array( array( 'ti' ), array( 'tir' ) ),
    	array( array( '' ), array( 'tem' ) ),
    	array( array( '' ), array( 'tiv' ) ),
    	array( array( '' ), array( 'tlh' ) ),
    	array( array( '' ), array( 'tli' ) ),
    	array( array( '' ), array( 'tpi' ) ),
    	array( array( '' ), array( 'tke' ) ),
    	array( array( '' ), array( 'tog' ) ),
    	array( array( 'to' ), array( 'ton' ) ),
    	array( array( '' ), array( 'tsi' ) ),
    	array( array( '' ), array( 'tso' ) ),
    	array( array( 'tn' ), array( 'tsn' ) ),
    	array( array( '' ), array( 'tum' ) ),
    	array( array( '' ), array( 'tup' ) ),
    	array( array( 'tr' ), array( 'tur' ) ),
    	array( array( '' ), array( 'ota' ) ),
    	array( array( 'tk' ), array( 'tuk' ) ),
    	array( array( '' ), array( 'tvl' ) ),
    	array( array( '' ), array( 'tyv' ) ),
    	array( array( 'tw' ), array( 'twi' ) ),
    	array( array( '' ), array( 'udm' ) ),
    	array( array( '' ), array( 'uga' ) ),
    	array( array( 'ug' ), array( 'uig' ) ),
    	array( array( 'uk' ), array( 'ukr' ) ),
    	array( array( '' ), array( 'umb' ) ),
    	array( array( '' ), array( 'und' ) ),
    	array( array( '' ), array( 'hsb' ) ),
    	array( array( 'ur' ), array( 'urd' ) ),
    	array( array( 'ug' ), array( 'uig' ) ),
    	array( array( 'uz' ), array( 'uzb' ) ),
    	array( array( '' ), array( 'vai' ) ),
    	array( array( 'ca' ), array( 'cat' ) ),
    	array( array( 've' ), array( 'ven' ) ),
    	array( array( 'vi' ), array( 'vie' ) ),
    	array( array( 'vo' ), array( 'vol' ) ),
    	array( array( '' ), array( 'vot' ) ),
    	array( array( '' ), array( 'wak' ) ),
    	array( array( '' ), array( 'wal' ) ),
    	array( array( 'wa' ), array( 'wln' ) ),
    	array( array( '' ), array( 'war' ) ),
    	array( array( '' ), array( 'was' ) ),
    	array( array( 'cy' ), array( 'wet', 'cym' ) ),
    	array( array( 'fy' ), array( 'fry' ) ),
    	array( array( 'wo' ), array( 'wol' ) ),
    	array( array( 'xh' ), array( 'xho' ) ),
    	array( array( '' ), array( 'sah' ) ),
    	array( array( '' ), array( 'yao' ) ),
    	array( array( '' ), array( 'yap' ) ),
    	array( array( 'yi' ), array( 'yid' ) ),
    	array( array( 'yo' ), array( 'yor' ) ),
    	array( array( '' ), array( 'ypk' ) ),
    	array( array( '' ), array( 'znd' ) ),
    	array( array( '' ), array( 'zap' ) ),
    	array( array( '' ), array( 'zen' ) ),
    	array( array( 'za' ), array( 'zha' ) ),
    	array( array( 'zu' ), array( 'zul' ) ),
    	array( array( '' ), array( 'zun' ) ),
    	
    );
    function convert( $lang )
    {        
    	if( strlen( $lang ) == 2 )
    	{
    	    foreach ( $this->languages as $language )
    	    {
    	        if ( $language[0][0] == $lang )
    	        {
    	            return $language[1][0];
    	        }
    	    }
    	}
    }
    /* not really important, this one; perhaps I could've put it inline with
 * the rest. */
function find_match($curlscore,$curcscore,$curgtlang,$langval,$charval,
                    $gtlang)
{
  if($curlscore < $langval) {
    $curlscore=$langval;
    $curcscore=$charval;
    $curgtlang=$gtlang;
  } else if ($curlscore == $langval) {
    if($curcscore < $charval) {
      $curcscore=$charval;
      $curgtlang=$gtlang;
    }
  }
  return array($curlscore, $curcscore, $curgtlang);
}

    function languages()
    {
    	$regionini = eZINI::instance( 'region.ini' );
        $regions = $regionini->groups();
        unset( $regions['Settings'] );
        $languages = array();
        foreach ( $regions as $key => $region )
        {
            $list = preg_split( '/[_-]/',  $key, 2 );
            if ( isset( $list[0] ) )
                $languages[$list[0]] = $list[0];
        }
        return $languages;
    }
    static function preferredLanguages( $format =  self::ISO936_2 )
    {
        $iso = new ezxISO936();
        $alscores = array();
        /* default to "everything is acceptable", as RFC2616 specifies */
        $acceptLang = ( ( $_SERVER["HTTP_ACCEPT_LANGUAGE"] == '' ) ? '*' : $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        $alparts = preg_split( "/,/", $acceptLang  );
        /* Parse the contents of the Accept-Language header.*/
        foreach( $alparts as $part )
        {
            $part=trim($part);
            if( preg_match( "/;/", $part ) )
            {
                $lang = preg_split("/;/",$part);
                $score = preg_split("/=/",$lang[1]);
                if( strpos( $lang[0], '-' ) !== false )
                {
                    list( $lang[0], $langalternate ) = preg_split( '/-/', $lang[0] );
                }
                $convert = $iso->convert( $lang[0] );
                if ( !array_key_exists( $convert, $alscores ) )
                {
                    $alscores[$convert]=$score[1];
                }
            }
            else
            {
                if( strpos( $part, '-' ) !== false )
                {
                    list( $part, $langalternate ) = preg_split( '/-/', $part );
                }
                $convert = $iso->convert( $part );
                if ( !array_key_exists( $convert, $alscores ) )
                {
                    $alscores[$convert]=1;
                }
            }
        }
        return $alscores;

  /* 
   * Loop through the available languages/encodings, and pick the one
   * with the highest score, excluding the ones with a charset the user
   * did not include.

  $curlscore=0;
  $curcscore=0;
  $curgtlang=NULL;
  foreach($gettextlangs as $gtlang) {

    $tmp1=preg_replace("/\_/","-",$gtlang);
    $tmp2=@preg_split("/\./",$tmp1);
    $allang=strtolower($tmp2[0]);
    $gtcs=strtoupper($tmp2[1]);
    $noct=@preg_split("/-/",$allang);

    $testvals=array(
         array($alscores[$allang], $acscores[$gtcs]),
	 array($alscores[$noct[0]], $acscores[$gtcs]),
	 array($alscores[$allang], $acscores["*"]),
	 array($alscores[$noct[0]], $acscores["*"]),
	 array($alscores["*"], $acscores[$gtcs]),
	 array($alscores["*"], $acscores["*"]));

    $found=FALSE;
    foreach($testvals as $tval) {
      if(!$found && isset($tval[0]) && isset($tval[1])) {
        $arr=ezxISO936::find_match($curlscore, $curcscore, $curgtlang, $tval[0],
	          $tval[1], $gtlang);
        $curlscore=$arr[0];
        $curcscore=$arr[1];
        $curgtlang=$arr[2];
	$found=TRUE;
      }
    }
  }
   */


    }
}
?>