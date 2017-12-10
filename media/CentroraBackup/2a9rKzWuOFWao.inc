<?php
$rules = array (
  0 => 
  array (
    'id' => '1',
    'filter' => '(?:"[^"]*[^-]?>)|(?:[^\\w\\s]\\s*\\/>)|(?:>")',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '4',
    'description' => 'Finds html breaking injections including whitespace attacks',
  ),
  1 => 
  array (
    'id' => '2',
    'filter' => '(?:"+.*[<=]\\s*"[^"]+")|(?:"\\s*\\w+\\s*=)|(?:>\\w=\\/)|(?:#.+\\)["\\s]*>)|(?:"\\s*(?:src|style|on\\w+)\\s*=\\s*")|(?:[^"]?"[,;\\s]+\\w*[\\[\\(])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '4',
    'description' => 'Finds attribute breaking injections including whitespace attacks',
  ),
  2 => 
  array (
    'id' => '3',
    'filter' => '(?:^>[\\w\\s]*<\\/?\\w{2,}>)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '2',
    'description' => 'Finds unquoted attribute breaking injections',
  ),
  3 => 
  array (
    'id' => '4',
    'filter' => '(?:[+\\/]\\s*name[\\W\\d]*[)+])|(?:;\\W*url\\s*=)|(?:[^\\w\\s\\/?:>]\\s*(?:location|referrer|name)\\s*[^\\/\\w\\s-])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '5',
    'description' => 'Detects url-, name-, JSON, and referrer-contained payload attacks',
  ),
  4 => 
  array (
    'id' => '5',
    'filter' => '(?:\\W\\s*hash\\s*[^\\w\\s-])|(?:\\w+=\\W*[^,]*,[^\\s(]\\s*\\()|(?:\\?"[^\\s"]":)|(?:(?<!\\/)__[a-z]+__)|(?:(?:^|[\\s)\\]\\}])(?:s|g)etter\\s*=)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '5',
    'description' => 'Detects hash-contained xss payload attacks, setter usage and property overloading',
  ),
  5 => 
  array (
    'id' => '6',
    'filter' => '(?:with\\s*\\(\\s*.+\\s*\\)\\s*\\w+\\s*\\()|(?:(?:do|while|for)\\s*\\([^)]*\\)\\s*\\{)|(?:\\/[\\w\\s]*\\[\\W*\\w)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '5',
    'description' => 'Detects self contained xss via with(), common loops and regex to string conversion',
  ),
  6 => 
  array (
    'id' => '7',
    'filter' => '(?:[=(].+\\?.+:)|(?:with\\([^)]*\\)\\))|(?:\\.\\s*source\\W)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '5',
    'description' => 'Detects JavaScript with(), ternary operators and XML predicate attacks',
  ),
  7 => 
  array (
    'id' => '8',
    'filter' => '(?:\\/\\w*\\s*\\)\\s*\\()|(?:\\([\\w\\s]+\\([\\w\\s]+\\)[\\w\\s]+\\))|(?:(?<!(?:mozilla\\/\\d\\.\\d\\s))\\([^)[]+\\[[^\\]]+\\][^)]*\\))|(?:[^\\s!][{([][^({[]+[{([][^}\\])]+[}\\])][\\s+",\\d]*[}\\])])|(?:"\\)?\\]\\W*\\[)|(?:=\\s*[^\\s:;]+\\s*[{([][^}\\])]+[}\\])];)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '5',
    'description' => 'Detects self-executing JavaScript functions',
  ),
  8 => 
  array (
    'id' => '9',
    'filter' => '(?:\\\\u00[a-f0-9]{2})|(?:\\\\x0*[a-f0-9]{2})|(?:\\\\\\d{2,3})',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '2',
    'description' => 'Detects the IE octal, hex and unicode entities',
  ),
  9 => 
  array (
    'id' => '10',
    'filter' => '(?:(?:\\/|\\\\)?\\.+(\\/|\\\\)(?:\\.+)?)|(?:\\w+\\.exe\\??\\s)|(?:;\\s*\\w+\\s*\\/[\\w*-]+\\/)|(?:\\d\\.\\dx\\|)|(?:%(?:c0\\.|af\\.|5c\\.))|(?:\\/(?:%2e){2})',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '8',
      1 => '7',
      2 => '6',
    ),
    'impact' => '5',
    'description' => 'Detects basic directory traversal',
  ),
  10 => 
  array (
    'id' => '11',
    'filter' => '(?:%c0%ae\\/)|(?:(?:\\/|\\\\)(home|conf|usr|etc|proc|opt|s?bin|local|dev|tmp|kern|[br]oot|sys|system|windows|winnt|program|%[a-z_-]{3,}%)(?:\\/|\\\\))|(?:(?:\\/|\\\\)inetpub|localstart\\.asp|boot\\.ini)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '8',
      1 => '7',
      2 => '6',
    ),
    'impact' => '5',
    'description' => 'Detects specific directory and path traversal',
  ),
  11 => 
  array (
    'id' => '12',
    'filter' => '(?:etc\\/\\W*passwd)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '8',
      1 => '7',
      2 => '6',
    ),
    'impact' => '5',
    'description' => 'Detects etc/passwd inclusion attempts',
  ),
  12 => 
  array (
    'id' => '13',
    'filter' => '(?:%u(?:ff|00|e\\d)\\w\\w)|(?:(?:%(?:e\\w|c[^3\\W]|))(?:%\\w\\w)(?:%\\w\\w)?)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '3',
    'description' => 'Detects halfwidth/fullwidth encoded unicode HTML breaking attempts',
  ),
  13 => 
  array (
    'id' => '14',
    'filter' => '(?:#@~\\^\\w+)|(?:\\w+script:|@import[^\\w]|;base64|base64,)|(?:\\w\\s*\\([\\w\\s]+,[\\w\\s]+,[\\w\\s]+,[\\w\\s]+,[\\w\\s]+,[\\w\\s]+\\))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '5',
    'description' => 'Detects possible includes, VBSCript/JScript encodeed and packed functions',
  ),
  14 => 
  array (
    'id' => '15',
    'filter' => '([^*:\\s\\w,.\\/?+-]\\s*)?(?<![a-z]\\s)(?<![a-z\\/_@\\-\\|])(\\s*return\\s*)?(?:create(?:element|attribute|textnode)|[a-z]+events?|setattribute|getelement\\w+|appendchild|createrange|createcontextualfragment|removenode|parentnode|decodeuricomponent|\\wettimeout|(?:ms)?setimmediate|option|useragent)(?(1)[^\\w%"]|(?:\\s*[^@\\s\\w%",.+\\-]))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '6',
    'description' => 'Detects JavaScript DOM/miscellaneous properties and methods',
  ),
  15 => 
  array (
    'id' => '16',
    'filter' => '([^*\\s\\w,.\\/?+-]\\s*)?(?<![a-mo-z]\\s)(?<![a-z\\/_@])(\\s*return\\s*)?(?:alert|inputbox|showmod(?:al|eless)dialog|showhelp|infinity|isnan|isnull|iterator|msgbox|executeglobal|expression|prompt|write(?:ln)?|confirm|dialog|urn|(?:un)?eval|exec|execscript|tostring|status|execute|window|unescape|navigate|jquery|getscript|extend|prototype)(?(1)[^\\w%"]|(?:\\s*[^@\\s\\w%",.:\\/+\\-]))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '5',
    'description' => 'Detects possible includes and typical script methods',
  ),
  16 => 
  array (
    'id' => '17',
    'filter' => '([^*:\\s\\w,.\\/?+-]\\s*)?(?<![a-z]\\s)(?<![a-z\\/_@])(\\s*return\\s*)?(?:hash|name|href|navigateandfind|source|pathname|close|constructor|port|protocol|assign|replace|back|forward|document|ownerdocument|window|top|this|self|parent|frames|_?content|date|cookie|innerhtml|innertext|csstext+?|outerhtml|print|moveby|resizeto|createstylesheet|stylesheets)(?(1)[^\\w%"]|(?:\\s*[^@\\/\\s\\w%.+\\-]))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '4',
    'description' => 'Detects JavaScript object properties and methods',
  ),
  17 => 
  array (
    'id' => '18',
    'filter' => '([^*:\\s\\w,.\\/?+-]\\s*)?(?<![a-z]\\s)(?<![a-z\\/_@\\-\\|])(\\s*return\\s*)?(?:join|pop|push|reverse|reduce|concat|map|shift|sp?lice|sort|unshift)(?(1)[^\\w%"]|(?:\\s*[^@\\s\\w%,.+\\-]))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '4',
    'description' => 'Detects JavaScript array properties and methods',
  ),
  18 => 
  array (
    'id' => '19',
    'filter' => '([^*:\\s\\w,.\\/?+-]\\s*)?(?<![a-z]\\s)(?<![a-z\\/_@\\-\\|])(\\s*return\\s*)?(?:set|atob|btoa|charat|charcodeat|charset|concat|crypto|frames|fromcharcode|indexof|lastindexof|match|navigator|toolbar|menubar|replace|regexp|slice|split|substr|substring|escape|\\w+codeuri\\w*)(?(1)[^\\w%"]|(?:\\s*[^@\\s\\w%,.+\\-]))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '4',
    'description' => 'Detects JavaScript string properties and methods',
  ),
  19 => 
  array (
    'id' => '20',
    'filter' => '(?:\\)\\s*\\[)|([^*":\\s\\w,.\\/?+-]\\s*)?(?<![a-z]\\s)(?<![a-z_@\\|])(\\s*return\\s*)?(?:globalstorage|sessionstorage|postmessage|callee|constructor|content|domain|prototype|try|catch|top|call|apply|url|function|object|array|string|math|if|for\\s*(?:each)?|elseif|case|switch|regex|boolean|location|(?:ms)?setimmediate|settimeout|setinterval|void|setexpression|namespace|while)(?(1)[^\\w%"]|(?:\\s*[^@\\s\\w%".+\\-\\/]))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '4',
    'description' => 'Detects JavaScript language constructs',
  ),
  20 => 
  array (
    'id' => '21',
    'filter' => '(?:,\\s*(?:alert|showmodaldialog|eval)\\s*,)|(?::\\s*eval\\s*[^\\s])|([^:\\s\\w,.\\/?+-]\\s*)?(?<![a-z\\/_@])(\\s*return\\s*)?(?:(?:document\\s*\\.)?(?:.+\\/)?(?:alert|eval|msgbox|showmod(?:al|eless)dialog|showhelp|prompt|write(?:ln)?|confirm|dialog|open))\\s*(?:[^.a-z\\s\\-]|(?:\\s*[^\\s\\w,.@\\/+-]))|(?:java[\\s\\/]*\\.[\\s\\/]*lang)|(?:\\w\\s*=\\s*new\\s+\\w+)|(?:&\\s*\\w+\\s*\\)[^,])|(?:\\+[\\W\\d]*new\\s+\\w+[\\W\\d]*\\+)|(?:document\\.\\w)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '3',
    'description' => 'Detects very basic XSS probings',
  ),
  21 => 
  array (
    'id' => '22',
    'filter' => '(?:=\\s*(?:top|this|window|content|self|frames|_content))|(?:\\/\\s*[gimx]*\\s*[)}])|(?:[^\\s]\\s*=\\s*script)|(?:\\.\\s*constructor)|(?:default\\s+xml\\s+namespace\\s*=)|(?:\\/\\s*\\+[^+]+\\s*\\+\\s*\\/)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '5',
    'description' => 'Detects advanced XSS probings via Script(), RexExp, constructors and XML namespaces',
  ),
  22 => 
  array (
    'id' => '23',
    'filter' => '(?:\\.\\s*\\w+\\W*=)|(?:\\W\\s*(?:location|document)\\s*\\W[^({[;]+[({[;])|(?:\\(\\w+\\?[:\\w]+\\))|(?:\\w{2,}\\s*=\\s*\\d+[^&\\w]\\w+)|(?:\\]\\s*\\(\\s*\\w+)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '5',
    'description' => 'Detects JavaScript location/document property access and window access obfuscation',
  ),
  23 => 
  array (
    'id' => '24',
    'filter' => '(?:[".]script\\s*\\()|(?:\\$\\$?\\s*\\(\\s*[\\w"])|(?:\\/[\\w\\s]+\\/\\.)|(?:=\\s*\\/\\w+\\/\\s*\\.)|(?:(?:this|window|top|parent|frames|self|content)\\[\\s*[(,"]*\\s*[\\w\\$])|(?:,\\s*new\\s+\\w+\\s*[,;)])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '5',
    'description' => 'Detects basic obfuscated JavaScript script injections',
  ),
  24 => 
  array (
    'id' => '25',
    'filter' => '(?:=\\s*[$\\w]\\s*[\\(\\[])|(?:\\(\\s*(?:this|top|window|self|parent|_?content)\\s*\\))|(?:src\\s*=s*(?:\\w+:|\\/\\/))|(?:\\w+\\[("\\w+"|\\w+\\|\\|))|(?:[\\d\\W]\\|\\|[\\d\\W]|\\W=\\w+,)|(?:\\/\\s*\\+\\s*[a-z"])|(?:=\\s*\\$[^([]*\\()|(?:=\\s*\\(\\s*")',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '5',
    'description' => 'Detects obfuscated JavaScript script injections',
  ),
  25 => 
  array (
    'id' => '26',
    'filter' => '(?:[^:\\s\\w]+\\s*[^\\w\\/](href|protocol|host|hostname|pathname|hash|port|cookie)[^\\w])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '4',
    'description' => 'Detects JavaScript cookie stealing and redirection attempts',
  ),
  26 => 
  array (
    'id' => '27',
    'filter' => '(?:(?:vbs|vbscript|data):.*[,+])|(?:\\w+\\s*=\\W*(?!https?)\\w+:)|(jar:\\w+:)|(=\\s*"?\\s*vbs(?:ript)?:)|(language\\s*=\\s?"?\\s*vbs(?:ript)?)|on\\w+\\s*=\\*\\w+\\-"?',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '5',
    ),
    'impact' => '5',
    'description' => 'Detects data: URL injections, VBS injections and common URI schemes',
  ),
  27 => 
  array (
    'id' => '28',
    'filter' => '(?:firefoxurl:\\w+\\|)|(?:(?:file|res|telnet|nntp|news|mailto|chrome)\\s*:\\s*[%&#xu\\/]+)|(wyciwyg|firefoxurl\\s*:\\s*\\/\\s*\\/)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '5',
      2 => '6',
      3 => '3',
    ),
    'impact' => '5',
    'description' => 'Detects IE firefoxurl injections, cache poisoning attempts and local file inclusion/execution',
  ),
  28 => 
  array (
    'id' => '29',
    'filter' => '(?:binding\\s?=|moz-binding|behavior\\s?=)|(?:[\\s\\/]style\\s*=\\s*[-\\\\])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '5',
    ),
    'impact' => '4',
    'description' => 'Detects bindings and behavior injections',
  ),
  29 => 
  array (
    'id' => '30',
    'filter' => '(?:=\\s*\\w+\\s*\\+\\s*")|(?:\\+=\\s*\\(\\s")|(?:!+\\s*[\\d.,]+\\w?\\d*\\s*\\?)|(?:=\\s*\\[s*\\])|(?:"\\s*\\+\\s*")|(?:[^\\s]\\[\\s*\\d+\\s*\\]\\s*[;+])|(?:"\\s*[&|]+\\s*")|(?:\\/\\s*\\?\\s*")|(?:\\/\\s*\\)\\s*\\[)|(?:\\d\\?.+:\\d)|(?:]\\s*\\[\\W*\\w)|(?:[^\\s]\\s*=\\s*\\/)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '4',
    'description' => 'Detects common XSS concatenation patterns 1/2',
  ),
  30 => 
  array (
    'id' => '31',
    'filter' => '(?:=\\s*\\d*\\.\\d*\\?\\d*\\.\\d*)|(?:[|&]{2,}\\s*")|(?:!\\d+\\.\\d*\\?")|(?:\\/:[\\w.]+,)|(?:=[\\d\\W\\s]*\\[[^]]+\\])|(?:\\?\\w+:\\w+)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
    ),
    'impact' => '4',
    'description' => 'Detects common XSS concatenation patterns 2/2',
  ),
  31 => 
  array (
    'id' => '32',
    'filter' => '(?:[^\\w\\s=]on(?!g\\&gt;)\\w+[^=_+-]*=[^$]+(?:\\W|\\&gt;)?)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '4',
    'description' => 'Detects possible event handlers',
  ),
  32 => 
  array (
    'id' => '33',
    'filter' => '(?:\\<\\w*:?\\s(?:[^\\>]*)t(?!rong))|(?:\\<scri)|(<\\w+:\\w+)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
    ),
    'impact' => '4',
    'description' => 'Detects obfuscated script tags and XML wrapped HTML',
  ),
  33 => 
  array (
    'id' => '34',
    'filter' => '(?:\\<\\/\\w+\\s\\w+)|(?:@(?:cc_on|set)[\\s@,"=])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '4',
    'description' => 'Detects attributes in closing tags and conditional compilation tokens',
  ),
  34 => 
  array (
    'id' => '35',
    'filter' => '(?:--[^\\n]*$)|(?:\\<!-|-->)|(?:[^*]\\/\\*|\\*\\/[^*])|(?:(?:[\\W\\d]#|--|{)$)|(?:\\/{3,}.*$)|(?:<!\\[\\W)|(?:\\]!>)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
    ),
    'impact' => '3',
    'description' => 'Detects common comment types',
  ),
  35 => 
  array (
    'id' => '36',
    'filter' => '(?:\\<base\\s+)|(?:<!(?:element|entity|\\[CDATA))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
    ),
    'impact' => '5',
    'description' => 'Detects base href injections and XML entity injections',
  ),
  36 => 
  array (
    'id' => '37',
    'filter' => '(?:\\<[\\/]?(?:[i]?frame|applet|isindex|marquee|keygen|script|audio|video|input|button|textarea|style|base|body|meta|link|object|embed|param|plaintext|xm\\w+|image|im(?:g|port)))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
      4 => '6',
    ),
    'impact' => '4',
    'description' => 'Detects possibly malicious html elements including some attributes',
  ),
  37 => 
  array (
    'id' => '38',
    'filter' => '(?:\\\\x[01fe][\\db-ce-f])|(?:%[01fe][\\db-ce-f])|(?:&#[01fe][\\db-ce-f])|(?:\\\\[01fe][\\db-ce-f])|(?:&#x[01fe][\\db-ce-f])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '7',
      1 => '5',
      2 => '2',
    ),
    'impact' => '5',
    'description' => 'Detects nullbytes and other dangerous characters',
  ),
  38 => 
  array (
    'id' => '39',
    'filter' => '(?:\\)\\s*when\\s*\\d+\\s*then)|(?:"\\s*(?:#|--|{))|(?:\\/\\*!\\s?\\d+)|(?:ch(?:a)?r\\s*\\(\\s*\\d)|(?:(?:(n?and|x?or|not)\\s+|\\|\\||\\&\\&)\\s*\\w+\\()',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
      2 => '6',
    ),
    'impact' => '6',
    'description' => 'Detects MySQL comments, conditions and ch(a)r injections',
  ),
  39 => 
  array (
    'id' => '40',
    'filter' => '(?:[\\s()]case\\s*\\()|(?:\\)\\s*like\\s*\\()|(?:having\\s*[^\\s]+\\s*[^\\w\\s])|(?:if\\s?\\([\\d\\w]\\s*[=<>~])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
      2 => '6',
    ),
    'impact' => '6',
    'description' => 'Detects conditional SQL injection attempts',
  ),
  40 => 
  array (
    'id' => '41',
    'filter' => '(?:"\\s*or\\s*"?\\d)|(?:\\\\x(?:23|27|3d))|(?:^.?"$)|(?:(?:^["\\\\]*(?:[\\d"]+|[^"]+"))+\\s*(?:n?and|x?or|not|\\|\\||\\&\\&)\\s*[\\w"[+&!@(),.-])|(?:[^\\w\\s]\\w+\\s*[|-]\\s*"\\s*\\w)|(?:@\\w+\\s+(and|or)\\s*["\\d]+)|(?:@[\\w-]+\\s(and|or)\\s*[^\\w\\s])|(?:[^\\w\\s:]\\s*\\d\\W+[^\\w\\s]\\s*".)|(?:\\Winformation_schema|table_name\\W)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
      2 => '6',
    ),
    'impact' => '6',
    'description' => 'Detects classic SQL injection probings 1/2',
  ),
  41 => 
  array (
    'id' => '42',
    'filter' => '(?:"\\s*\\*.+(?:or|id)\\W*"\\d)|(?:\\^")|(?:^[\\w\\s"-]+(?<=and\\s)(?<=or\\s)(?<=xor\\s)(?<=nand\\s)(?<=not\\s)(?<=\\|\\|)(?<=\\&\\&)\\w+\\()|(?:"[\\s\\d]*[^\\w\\s]+\\W*\\d\\W*.*["\\d])|(?:"\\s*[^\\w\\s?]+\\s*[^\\w\\s]+\\s*")|(?:"\\s*[^\\w\\s]+\\s*[\\W\\d].*(?:#|--))|(?:".*\\*\\s*\\d)|(?:"\\s*or\\s[^\\d]+[\\w-]+.*\\d)|(?:[()*<>%+-][\\w-]+[^\\w\\s]+"[^,])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
      2 => '6',
    ),
    'impact' => '6',
    'description' => 'Detects classic SQL injection probings 2/2',
  ),
  42 => 
  array (
    'id' => '43',
    'filter' => '(?:\\d"\\s+"\\s+\\d)|(?:^admin\\s*"|(\\/\\*)+"+\\s?(?:--|#|\\/\\*|{)?)|(?:"\\s*or[\\w\\s-]+\\s*[+<>=(),-]\\s*[\\d"])|(?:"\\s*[^\\w\\s]?=\\s*")|(?:"\\W*[+=]+\\W*")|(?:"\\s*[!=|][\\d\\s!=+-]+.*["(].*$)|(?:"\\s*[!=|][\\d\\s!=]+.*\\d+$)|(?:"\\s*like\\W+[\\w"(])|(?:\\sis\\s*0\\W)|(?:where\\s[\\s\\w\\.,-]+\\s=)|(?:"[<>~]+")',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
      2 => '6',
    ),
    'impact' => '7',
    'description' => 'Detects basic SQL authentication bypass attempts 1/3',
  ),
  43 => 
  array (
    'id' => '44',
    'filter' => '(?:union\\s*(?:all|distinct|[(!@]*)\\s*[([]*\\s*select)|(?:\\w+\\s+like\\s+\\")|(?:like\\s*"\\%)|(?:"\\s*like\\W*["\\d])|(?:"\\s*(?:n?and|x?or|not |\\|\\||\\&\\&)\\s+[\\s\\w]+=\\s*\\w+\\s*having)|(?:"\\s*\\*\\s*\\w+\\W+")|(?:"\\s*[^?\\w\\s=.,;)(]+\\s*[(@"]*\\s*\\w+\\W+\\w)|(?:select\\s*[\\[\\]()\\s\\w\\.,"-]+from)|(?:find_in_set\\s*\\()',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
      2 => '6',
    ),
    'impact' => '7',
    'description' => 'Detects basic SQL authentication bypass attempts 2/3',
  ),
  44 => 
  array (
    'id' => '45',
    'filter' => '(?:in\\s*\\(+\\s*select)|(?:(?:n?and|x?or|not |\\|\\||\\&\\&)\\s+[\\s\\w+]+(?:regexp\\s*\\(|sounds\\s+like\\s*"|[=\\d]+x))|("\\s*\\d\\s*(?:--|#))|(?:"[%&<>^=]+\\d\\s*(=|or))|(?:"\\W+[\\w+-]+\\s*=\\s*\\d\\W+")|(?:"\\s*is\\s*\\d.+"?\\w)|(?:"\\|?[\\w-]{3,}[^\\w\\s.,]+")|(?:"\\s*is\\s*[\\d.]+\\s*\\W.*")',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
      2 => '6',
    ),
    'impact' => '7',
    'description' => 'Detects basic SQL authentication bypass attempts 3/3',
  ),
  45 => 
  array (
    'id' => '46',
    'filter' => '(?:[\\d\\W]\\s+as\\s*["\\w]+\\s*from)|(?:^[\\W\\d]+\\s*(?:union|select|create|rename|truncate|load|alter|delete|update|insert|desc))|(?:(?:select|create|rename|truncate|load|alter|delete|update|insert|desc)\\s+(?:(?:group_)concat|char|load_file)\\s?\\(?)|(?:end\\s*\\);)|("\\s+regexp\\W)|(?:[\\s(]load_file\\s*\\()',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
      2 => '6',
    ),
    'impact' => '5',
    'description' => 'Detects concatenated basic SQL injection and SQLLFI attempts',
  ),
  46 => 
  array (
    'id' => '47',
    'filter' => '(?:@.+=\\s*\\(\\s*select)|(?:\\d+\\s*or\\s*\\d+\\s*[\\-+])|(?:\\/\\w+;?\\s+(?:having|and|or|select)\\W)|(?:\\d\\s+group\\s+by.+\\()|(?:(?:;|#|--)\\s*(?:drop|alter))|(?:(?:;|#|--)\\s*(?:update|insert)\\s*\\w{2,})|(?:[^\\w]SET\\s*@\\w+)|(?:(?:n?and|x?or|not |\\|\\||\\&\\&)[\\s(]+\\w+[\\s)]*[!=+]+[\\s\\d]*["=()])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '6',
    'description' => 'Detects chained SQL injection attempts 1/2',
  ),
  47 => 
  array (
    'id' => '48',
    'filter' => '(?:"\\s+and\\s*=\\W)|(?:\\(\\s*select\\s*\\w+\\s*\\()|(?:\\*\\/from)|(?:\\+\\s*\\d+\\s*\\+\\s*@)|(?:\\w"\\s*(?:[-+=|@]+\\s*)+[\\d(])|(?:coalesce\\s*\\(|@@\\w+\\s*[^\\w\\s])|(?:\\W!+"\\w)|(?:";\\s*(?:if|while|begin))|(?:"[\\s\\d]+=\\s*\\d)|(?:order\\s+by\\s+if\\w*\\s*\\()|(?:[\\s(]+case\\d*\\W.+[tw]hen[\\s(])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '6',
    'description' => 'Detects chained SQL injection attempts 2/2',
  ),
  48 => 
  array (
    'id' => '49',
    'filter' => '(?:(select|;)\\s+(?:benchmark|if|sleep)\\s*?\\(\\s*\\(?\\s*\\w+)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '4',
    'description' => 'Detects SQL benchmark and sleep injection attempts including conditional queries',
  ),
  49 => 
  array (
    'id' => '50',
    'filter' => '(?:create\\s+function\\s+\\w+\\s+returns)|(?:;\\s*(?:select|create|rename|truncate|load|alter|delete|update|insert|desc)\\s*[\\[(]?\\w{2,})',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '6',
    'description' => 'Detects MySQL UDF injection and other data/structure manipulation attempts',
  ),
  50 => 
  array (
    'id' => '51',
    'filter' => '(?:alter\\s*\\w+.*character\\s+set\\s+\\w+)|(";\\s*waitfor\\s+time\\s+")|(?:";.*:\\s*goto)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '6',
    'description' => 'Detects MySQL charset switch and MSSQL DoS attempts',
  ),
  51 => 
  array (
    'id' => '52',
    'filter' => '(?:procedure\\s+analyse\\s*\\()|(?:;\\s*(declare|open)\\s+[\\w-]+)|(?:create\\s+(procedure|function)\\s*\\w+\\s*\\(\\s*\\)\\s*-)|(?:declare[^\\w]+[@#]\\s*\\w+)|(exec\\s*\\(\\s*@)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '7',
    'description' => 'Detects MySQL and PostgreSQL stored procedure/function injections',
  ),
  52 => 
  array (
    'id' => '53',
    'filter' => '(?:select\\s*pg_sleep)|(?:waitfor\\s*delay\\s?"+\\s?\\d)|(?:;\\s*shutdown\\s*(?:;|--|#|\\/\\*|{))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '5',
    'description' => 'Detects Postgres pg_sleep injection, waitfor delay attacks and database shutdown attempts',
  ),
  53 => 
  array (
    'id' => '54',
    'filter' => '(?:\\sexec\\s+xp_cmdshell)|(?:"\\s*!\\s*["\\w])|(?:from\\W+information_schema\\W)|(?:(?:(?:current_)?user|database|schema|connection_id)\\s*\\([^\\)]*)|(?:";?\\s*(?:select|union|having)\\s*[^\\s])|(?:\\wiif\\s*\\()|(?:exec\\s+master\\.)|(?:union select @)|(?:union[\\w(\\s]*select)|(?:select.*\\w?user\\()|(?:into[\\s+]+(?:dump|out)file\\s*")',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '5',
    'description' => 'Detects MSSQL code execution and information gathering attempts',
  ),
  54 => 
  array (
    'id' => '55',
    'filter' => '(?:merge.*using\\s*\\()|(execute\\s*immediate\\s*")|(?:\\W+\\d*\\s*having\\s*[^\\s\\-])|(?:match\\s*[\\w(),+-]+\\s*against\\s*\\()',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '5',
    'description' => 'Detects MATCH AGAINST, MERGE, EXECUTE IMMEDIATE and HAVING injections',
  ),
  55 => 
  array (
    'id' => '56',
    'filter' => '(?:,.*[)\\da-f"]"(?:".*"|\\Z|[^"]+))|(?:\\Wselect.+\\W*from)|((?:select|create|rename|truncate|load|alter|delete|update|insert|desc)\\s*\\(\\s*space\\s*\\()',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '5',
    'description' => 'Detects MySQL comment-/space-obfuscated injections and backtick termination',
  ),
  56 => 
  array (
    'id' => '57',
    'filter' => '(?:@[\\w-]+\\s*\\()|(?:]\\s*\\(\\s*["!]\\s*\\w)|(?:<[?%](?:php)?.*(?:[?%]>)?)|(?:;[\\s\\w|]*\\$\\w+\\s*=)|(?:\\$\\w+\\s*=(?:(?:\\s*\\$?\\w+\\s*[(;])|\\s*".*"))|(?:;\\s*\\{\\W*\\w+\\s*\\()',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '7',
      1 => '5',
      2 => '6',
    ),
    'impact' => '7',
    'description' => 'Detects code injection attempts 1/3',
  ),
  57 => 
  array (
    'id' => '58',
    'filter' => '(?:(?:[;]+|(<[?%](?:php)?)).*(?:define|eval|file_get_contents|include|require|require_once|set|shell_exec|phpinfo|system|passthru|preg_\\w+|execute)\\s*["(@])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '7',
      1 => '5',
      2 => '6',
    ),
    'impact' => '7',
    'description' => 'Detects code injection attempts 2/3',
  ),
  58 => 
  array (
    'id' => '59',
    'filter' => '(?:(?:[;]+|(<[?%](?:php)?)).*[^\\w](?:echo|print|print_r|var_dump|[fp]open))|(?:;\\s*rm\\s+-\\w+\\s+)|(?:;.*{.*\\$\\w+\\s*=)|(?:\\$\\w+\\s*\\[\\]\\s*=\\s*)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '7',
      1 => '5',
      2 => '6',
    ),
    'impact' => '7',
    'description' => 'Detects code injection attempts 3/3',
  ),
  59 => 
  array (
    'id' => '60',
    'filter' => '(?:function[^(]*\\([^)]*\\))|(?:(?:delete|void|throw|instanceof|new|typeof)[^\\w.]+\\w+\\s*[([])|([)\\]]\\s*\\.\\s*\\w+\\s*=)|(?:\\(\\s*new\\s+\\w+\\s*\\)\\.)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '7',
      1 => '5',
      2 => '6',
    ),
    'impact' => '5',
    'description' => 'Detects common function declarations and special JS operators',
  ),
  60 => 
  array (
    'id' => '61',
    'filter' => '(?:[\\w.-]+@[\\w.-]+%(?:[01][\\db-ce-f])+\\w+:)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '7',
      1 => '11',
    ),
    'impact' => '5',
    'description' => 'Detects common mail header injections',
  ),
  61 => 
  array (
    'id' => '62',
    'filter' => '(?:\\.pl\\?\\w+=\\w?\\|\\w+;)|(?:\\|\\(\\w+=\\*)|(?:\\*\\s*\\)+\\s*;)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '6',
      1 => '5',
    ),
    'impact' => '5',
    'description' => 'Detects perl echo shellcode injection and LDAP vectors',
  ),
  62 => 
  array (
    'id' => '63',
    'filter' => '(?:(^|\\W)const\\s+[\\w\\-]+\\s*=)|(?:(?:do|for|while)\\s*\\([^;]+;+\\))|(?:(?:^|\\W)on\\w+\\s*=[\\w\\W]*(?:on\\w+|alert|eval|print|confirm|prompt))|(?:groups=\\d+\\(\\w+\\))|(?:(.)\\1{128,})',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '5',
      1 => '9',
    ),
    'impact' => '5',
    'description' => 'Detects basic XSS DoS attempts',
  ),
  63 => 
  array (
    'id' => '64',
    'filter' => '(?:\\({2,}\\+{2,}:{2,})|(?:\\({2,}\\+{2,}:+)|(?:\\({3,}\\++:{2,})|(?:\\$\\[!!!\\])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
      2 => '7',
      3 => '5',
      4 => '6',
    ),
    'impact' => '7',
    'description' => 'Detects unknown attack vectors based on PHPIDS Centrifuge detection',
  ),
  64 => 
  array (
    'id' => '65',
    'filter' => '(?:[\\s\\/"]+[-\\w\\/\\\\\\*]+\\s*=.+(?:\\/\\s*>))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '4',
    'description' => 'Finds attribute breaking injections including obfuscated attributes',
  ),
  65 => 
  array (
    'id' => '66',
    'filter' => '(?:(?:msgbox|eval)\\s*\\+|(?:language\\s*=\\*vbscript))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '4',
    'description' => 'Finds basic VBScript injection attempts',
  ),
  66 => 
  array (
    'id' => '67',
    'filter' => '(?:\\[\\$(?:ne|eq|lte?|gte?|n?in|mod|all|size|exists|type|slice|or)\\])',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
    ),
    'impact' => '4',
    'description' => 'Finds basic MongoDB SQL injection attempts',
  ),
  67 => 
  array (
    'id' => '68',
    'filter' => '(?:[\\s\\d\\/"]+(?:on\\w+|style|poster|background)=[$"\\w])|(?:-type\\s*:\\s*multipart)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '2',
      1 => '3',
    ),
    'impact' => '6',
    'description' => 'Finds malicious attribute injection attempts and MHTML attacks',
  ),
  68 => 
  array (
    'id' => '69',
    'filter' => '(?:(sleep\\((\\s*)(\\d*)(\\s*)\\)|benchmark\\((.*)\\,(.*)\\)))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '4',
    'description' => 'Detects blind sqli tests using sleep() or benchmark().',
  ),
  69 => 
  array (
    'id' => '70',
    'filter' => '(?:(\\%SYSTEMROOT\\%))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '10',
      1 => '7',
    ),
    'impact' => '4',
    'description' => 'An attacker is trying to locate a file to read or write.',
  ),
  70 => 
  array (
    'id' => '72',
    'filter' => '(?:(union(.*)select(.*)from))',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '3',
    'description' => 'Looking for basic sql injection. Common attack string for mysql, oracle and others.',
  ),
  71 => 
  array (
    'id' => '73',
    'filter' => '(?:^(-0000023456|4294967295|4294967296|2147483648|2147483647|0000012345|-2147483648|-2147483649|0000023456|2.2250738585072007e-308|1e309)$)',
    'action' => '1',
    'attacktype' => 
    array (
      0 => '4',
      1 => '7',
    ),
    'impact' => '3',
    'description' => 'Looking for integer overflow attacks, these are taken from skipfish, except 2.2250738585072007e-308 is the "magic number" crash',
  ),
);