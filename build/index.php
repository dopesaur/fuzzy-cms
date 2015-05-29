<?php function array_get (array $array, $key, $default = false) {if (isset($array[$key])) {return $array[$key];}return $default;}function is_admin ($authorized = false) {static $admin = false;if ($authorized) {$admin = $authorized;}return $admin === true;}function auth_user ($username, $password) {if ($username === 'admin' &&$password === md5('123456')) {return is_admin(true);}return false;}function dispatch ($route) {$route = trim($route, '/');$segments = explode('/', $route);$prefix = array_shift($segments);$prefix = $prefix ? $prefix : 'index';$suffix = array_shift($segments);$suffix = $suffix ? $suffix : 'index';$function = "route_{$prefix}_{$suffix}";$function = str_replace('-', '_', $function);$function = preg_replace('/[^\w\d_]/', '', $function);$function = trim($function, '_');if (!function_exists($function)) {not_found();}call_user_func_array($function, $segments);}function base_url ($root = null, $base = null) {$root = trim($root ? $root : $_SERVER['DOCUMENT_ROOT'], '/');$base = trim($base ? $base : BASEPATH, '/');if ($root === $base) {return '';}$base_url = substr($base, strlen($root));return trim($base_url, '/');}function url () {$url = '';if (func_num_args() !== 0) {$url = implode('/', func_get_args());}$url = base_url() . "/$url/";$url = trim($url, '/');return "/$url";}function db_connect ($path = '') {static $db = null;if ($db) {return $db;}$dsn = "sqlite:$path";$db = new PDO($dsn);$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);return $db;}function db_query ($query, array $parameters = array()) {$db = db_connect();$statement = $db->prepare($query);$statement->execute($parameters);return $statement;}function db_select ($query, array $parameters = array(), $one = false) {$statement = db_query($query, $parameters);$result = $one ? $statement->fetch() : $statement->fetchAll();return $result ? $result : array();}function db_browse ($table, $fields = '*') {$query = "SELECT $fields FROM $table ORDER BY id DESC";return db_select($query);}function db_find ($table, $fields = '*', $id) {return db_select("SELECT $fields FROM $table WHERE id = ?", array($id), true);}function db_insert ($table, array $data) {if (empty($data)) {return 0;}$columns = array_keys($data);$columns = array_map(function ($key) {return "`$key`";}, $columns);$columns = implode(',', $columns);$values = str_repeat('?,', count($data));$values = chop($values, ',');$query = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $columns, $values);db_query($query, array_values($data));return db_connect()->lastInsertId();}function db_update ($table, array $data, $id) {if (empty($data)) {return 0;}$key_values = array_map(function ($key) {return "`$key` = ?";},array_keys($data));$key_values = implode(', ', $key_values);$query = sprintf('UPDATE %s SET %s WHERE id = ?', $table, $key_values);$data   = array_values($data);$data[] = $id;return db_query($query, $data)->rowCount();}function is_post () {$method = array_get($_SERVER, 'REQUEST_METHOD', 'get');return strtolower($method) === 'post';}function theme ($new_theme = '') {static $theme = 'default';if ($new_theme) {$theme = $new_theme;require_once sprintf('%s/themes/%s.php', BASEPATH, $theme);}return $theme;}function view ($view, array $data = array()) {$view = str_replace('/', '_', $view);$view = preg_replace('/[^\w\d_]/', '', $view);$theme = theme();$function = "theme_{$theme}_{$view}";$function($data);}function layout ($view, array $data = array()) {$data['view'] = $view;view('layout', $data);}function not_found () {header('HTTP/1.1 404 Not Found');die('404 - Not Found');}function redirect ($path) {$path = trim($path, '/');header("Location: /$path") and exit;}function markdown ($markdown) {static $parse = null;$parse or $parse = new Parsedown;return $parse->text($markdown);}function clamp ($int, $min, $max) {$int = max($int, $min);return min($int, $max);}function pagination ($total, $limit, $page) {$page = (int)$page;$pages = ceil($total / $limit);$items = range(1, $pages);$page = clamp($page, 1, $pages);$offset = $limit * ($page - 1);return compact('pages', 'items', 'page', 'offset', 'limit');}class Parsedown{const version = '1.5.3';function text($text){$this->DefinitionData = array();$text = str_replace(array("\r\n", "\r"), "\n", $text);$text = trim($text, "\n");$lines = explode("\n", $text);$markup = $this->lines($lines);$markup = trim($markup, "\n");return $markup;}function setBreaksEnabled($breaksEnabled){$this->breaksEnabled = $breaksEnabled;return $this;}protected $breaksEnabled;function setMarkupEscaped($markupEscaped){$this->markupEscaped = $markupEscaped;return $this;}protected $markupEscaped;function setUrlsLinked($urlsLinked){$this->urlsLinked = $urlsLinked;return $this;}protected $urlsLinked = true;protected $BlockTypes = array('#' => array('Header'),'*' => array('Rule', 'List'),'+' => array('List'),'-' => array('SetextHeader', 'Table', 'Rule', 'List'),'0' => array('List'),'1' => array('List'),'2' => array('List'),'3' => array('List'),'4' => array('List'),'5' => array('List'),'6' => array('List'),'7' => array('List'),'8' => array('List'),'9' => array('List'),':' => array('Table'),'<' => array('Comment', 'Markup'),'=' => array('SetextHeader'),'>' => array('Quote'),'[' => array('Reference'),'_' => array('Rule'),'`' => array('FencedCode'),'|' => array('Table'),'~' => array('FencedCode'),);protected $DefinitionTypes = array('[' => array('Reference'),);protected $unmarkedBlockTypes = array('Code',);private function lines(array $lines){$CurrentBlock = null;foreach ($lines as $line){if (chop($line) === ''){if (isset($CurrentBlock)){$CurrentBlock['interrupted'] = true;}continue;}if (strpos($line, "\t") !== false){$parts = explode("\t", $line);$line = $parts[0];unset($parts[0]);foreach ($parts as $part){$shortage = 4 - mb_strlen($line, 'utf-8') % 4;$line .= str_repeat(' ', $shortage);$line .= $part;}}$indent = 0;while (isset($line[$indent]) and $line[$indent] === ' '){$indent ++;}$text = $indent > 0 ? substr($line, $indent) : $line;$Line = array('body' => $line, 'indent' => $indent, 'text' => $text);if (isset($CurrentBlock['incomplete'])){$Block = $this->{'block'.$CurrentBlock['type'].'Continue'}($Line, $CurrentBlock);if (isset($Block)){$CurrentBlock = $Block;continue;}else{if (method_exists($this, 'block'.$CurrentBlock['type'].'Complete')){$CurrentBlock = $this->{'block'.$CurrentBlock['type'].'Complete'}($CurrentBlock);}unset($CurrentBlock['incomplete']);}}$marker = $text[0];$blockTypes = $this->unmarkedBlockTypes;if (isset($this->BlockTypes[$marker])){foreach ($this->BlockTypes[$marker] as $blockType){$blockTypes []= $blockType;}}foreach ($blockTypes as $blockType){$Block = $this->{'block'.$blockType}($Line, $CurrentBlock);if (isset($Block)){$Block['type'] = $blockType;if ( ! isset($Block['identified'])){$Blocks []= $CurrentBlock;$Block['identified'] = true;}if (method_exists($this, 'block'.$blockType.'Continue')){$Block['incomplete'] = true;}$CurrentBlock = $Block;continue 2;}}if (isset($CurrentBlock) and ! isset($CurrentBlock['type']) and ! isset($CurrentBlock['interrupted'])){$CurrentBlock['element']['text'] .= "\n".$text;}else{$Blocks []= $CurrentBlock;$CurrentBlock = $this->paragraph($Line);$CurrentBlock['identified'] = true;}}if (isset($CurrentBlock['incomplete']) and method_exists($this, 'block'.$CurrentBlock['type'].'Complete')){$CurrentBlock = $this->{'block'.$CurrentBlock['type'].'Complete'}($CurrentBlock);}$Blocks []= $CurrentBlock;unset($Blocks[0]);$markup = '';foreach ($Blocks as $Block){if (isset($Block['hidden'])){continue;}$markup .= "\n";$markup .= isset($Block['markup']) ? $Block['markup'] : $this->element($Block['element']);}$markup .= "\n";return $markup;}protected function blockCode($Line, $Block = null){if (isset($Block) and ! isset($Block['type']) and ! isset($Block['interrupted'])){return;}if ($Line['indent'] >= 4){$text = substr($Line['body'], 4);$Block = array('element' => array('name' => 'pre','handler' => 'element','text' => array('name' => 'code','text' => $text,),),);return $Block;}}protected function blockCodeContinue($Line, $Block){if ($Line['indent'] >= 4){if (isset($Block['interrupted'])){$Block['element']['text']['text'] .= "\n";unset($Block['interrupted']);}$Block['element']['text']['text'] .= "\n";$text = substr($Line['body'], 4);$Block['element']['text']['text'] .= $text;return $Block;}}protected function blockCodeComplete($Block){$text = $Block['element']['text']['text'];$text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');$Block['element']['text']['text'] = $text;return $Block;}protected function blockComment($Line){if ($this->markupEscaped){return;}if (isset($Line['text'][3]) and $Line['text'][3] === '-' and $Line['text'][2] === '-' and $Line['text'][1] === '!'){$Block = array('markup' => $Line['body'],);if (preg_match('/-->$/', $Line['text'])){$Block['closed'] = true;}return $Block;}}protected function blockCommentContinue($Line, array $Block){if (isset($Block['closed'])){return;}$Block['markup'] .= "\n" . $Line['body'];if (preg_match('/-->$/', $Line['text'])){$Block['closed'] = true;}return $Block;}protected function blockFencedCode($Line){if (preg_match('/^(['.$Line['text'][0].']{3,})[ ]*([\w-]+)?[ ]*$/', $Line['text'], $matches)){$Element = array('name' => 'code','text' => '',);if (isset($matches[2])){$class = 'language-'.$matches[2];$Element['attributes'] = array('class' => $class,);}$Block = array('char' => $Line['text'][0],'element' => array('name' => 'pre','handler' => 'element','text' => $Element,),);return $Block;}}protected function blockFencedCodeContinue($Line, $Block){if (isset($Block['complete'])){return;}if (isset($Block['interrupted'])){$Block['element']['text']['text'] .= "\n";unset($Block['interrupted']);}if (preg_match('/^'.$Block['char'].'{3,}[ ]*$/', $Line['text'])){$Block['element']['text']['text'] = substr($Block['element']['text']['text'], 1);$Block['complete'] = true;return $Block;}$Block['element']['text']['text'] .= "\n".$Line['body'];;return $Block;}protected function blockFencedCodeComplete($Block){$text = $Block['element']['text']['text'];$text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');$Block['element']['text']['text'] = $text;return $Block;}protected function blockHeader($Line){if (isset($Line['text'][1])){$level = 1;while (isset($Line['text'][$level]) and $Line['text'][$level] === '#'){$level ++;}if ($level > 6){return;}$text = trim($Line['text'], '# ');$Block = array('element' => array('name' => 'h' . min(6, $level),'text' => $text,'handler' => 'line',),);return $Block;}}protected function blockList($Line){list($name, $pattern) = $Line['text'][0] <= '-' ? array('ul', '[*+-]') : array('ol', '[0-9]+[.]');if (preg_match('/^('.$pattern.'[ ]+)(.*)/', $Line['text'], $matches)){$Block = array('indent' => $Line['indent'],'pattern' => $pattern,'element' => array('name' => $name,'handler' => 'elements',),);$Block['li'] = array('name' => 'li','handler' => 'li','text' => array($matches[2],),);$Block['element']['text'] []= & $Block['li'];return $Block;}}protected function blockListContinue($Line, array $Block){if ($Block['indent'] === $Line['indent'] and preg_match('/^'.$Block['pattern'].'(?:[ ]+(.*)|$)/', $Line['text'], $matches)){if (isset($Block['interrupted'])){$Block['li']['text'] []= '';unset($Block['interrupted']);}unset($Block['li']);$text = isset($matches[1]) ? $matches[1] : '';$Block['li'] = array('name' => 'li','handler' => 'li','text' => array($text,),);$Block['element']['text'] []= & $Block['li'];return $Block;}if ($Line['text'][0] === '[' and $this->blockReference($Line)){return $Block;}if ( ! isset($Block['interrupted'])){$text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);$Block['li']['text'] []= $text;return $Block;}if ($Line['indent'] > 0){$Block['li']['text'] []= '';$text = preg_replace('/^[ ]{0,4}/', '', $Line['body']);$Block['li']['text'] []= $text;unset($Block['interrupted']);return $Block;}}protected function blockQuote($Line){if (preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)){$Block = array('element' => array('name' => 'blockquote','handler' => 'lines','text' => (array) $matches[1],),);return $Block;}}protected function blockQuoteContinue($Line, array $Block){if ($Line['text'][0] === '>' and preg_match('/^>[ ]?(.*)/', $Line['text'], $matches)){if (isset($Block['interrupted'])){$Block['element']['text'] []= '';unset($Block['interrupted']);}$Block['element']['text'] []= $matches[1];return $Block;}if ( ! isset($Block['interrupted'])){$Block['element']['text'] []= $Line['text'];return $Block;}}protected function blockRule($Line){if (preg_match('/^(['.$Line['text'][0].'])([ ]*\1){2,}[ ]*$/', $Line['text'])){$Block = array('element' => array('name' => 'hr'),);return $Block;}}protected function blockSetextHeader($Line, array $Block = null){if ( ! isset($Block) or isset($Block['type']) or isset($Block['interrupted'])){return;}if (chop($Line['text'], $Line['text'][0]) === ''){$Block['element']['name'] = $Line['text'][0] === '=' ? 'h1' : 'h2';return $Block;}}protected function blockMarkup($Line){if ($this->markupEscaped){return;}if (preg_match('/^<(\w*)(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*(\/)?>/', $Line['text'], $matches)){if (in_array($matches[1], $this->textLevelElements)){return;}$Block = array('name' => $matches[1],'depth' => 0,'markup' => $Line['text'],);$length = strlen($matches[0]);$remainder = substr($Line['text'], $length);if (trim($remainder) === ''){if (isset($matches[2]) or in_array($matches[1], $this->voidElements)){$Block['closed'] = true;$Block['void'] = true;}}else{if (isset($matches[2]) or in_array($matches[1], $this->voidElements)){return;}if (preg_match('/<\/'.$matches[1].'>[ ]*$/i', $remainder)){$Block['closed'] = true;}}return $Block;}}protected function blockMarkupContinue($Line, array $Block){if (isset($Block['closed'])){return;}if (preg_match('/^<'.$Block['name'].'(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*>/i', $Line['text'])){$Block['depth'] ++;}if (preg_match('/(.*?)<\/'.$Block['name'].'>[ ]*$/i', $Line['text'], $matches)){if ($Block['depth'] > 0){$Block['depth'] --;}else{$Block['closed'] = true;}}if (isset($Block['interrupted'])){$Block['markup'] .= "\n";unset($Block['interrupted']);}$Block['markup'] .= "\n".$Line['body'];return $Block;}protected function blockReference($Line){if (preg_match('/^\[(.+?)\]:[ ]*<?(\S+?)>?(?:[ ]+["\'(](.+)["\')])?[ ]*$/', $Line['text'], $matches)){$id = strtolower($matches[1]);$Data = array('url' => $matches[2],'title' => null,);if (isset($matches[3])){$Data['title'] = $matches[3];}$this->DefinitionData['Reference'][$id] = $Data;$Block = array('hidden' => true,);return $Block;}}protected function blockTable($Line, array $Block = null){if ( ! isset($Block) or isset($Block['type']) or isset($Block['interrupted'])){return;}if (strpos($Block['element']['text'], '|') !== false and chop($Line['text'], ' -:|') === ''){$alignments = array();$divider = $Line['text'];$divider = trim($divider);$divider = trim($divider, '|');$dividerCells = explode('|', $divider);foreach ($dividerCells as $dividerCell){$dividerCell = trim($dividerCell);if ($dividerCell === ''){continue;}$alignment = null;if ($dividerCell[0] === ':'){$alignment = 'left';}if (substr($dividerCell, - 1) === ':'){$alignment = $alignment === 'left' ? 'center' : 'right';}$alignments []= $alignment;}$HeaderElements = array();$header = $Block['element']['text'];$header = trim($header);$header = trim($header, '|');$headerCells = explode('|', $header);foreach ($headerCells as $index => $headerCell){$headerCell = trim($headerCell);$HeaderElement = array('name' => 'th','text' => $headerCell,'handler' => 'line',);if (isset($alignments[$index])){$alignment = $alignments[$index];$HeaderElement['attributes'] = array('style' => 'text-align: '.$alignment.';',);}$HeaderElements []= $HeaderElement;}$Block = array('alignments' => $alignments,'identified' => true,'element' => array('name' => 'table','handler' => 'elements',),);$Block['element']['text'] []= array('name' => 'thead','handler' => 'elements',);$Block['element']['text'] []= array('name' => 'tbody','handler' => 'elements','text' => array(),);$Block['element']['text'][0]['text'] []= array('name' => 'tr','handler' => 'elements','text' => $HeaderElements,);return $Block;}}protected function blockTableContinue($Line, array $Block){if (isset($Block['interrupted'])){return;}if ($Line['text'][0] === '|' or strpos($Line['text'], '|')){$Elements = array();$row = $Line['text'];$row = trim($row);$row = trim($row, '|');preg_match_all('/(?:(\\\\[|])|[^|`]|`[^`]+`|`)+/', $row, $matches);foreach ($matches[0] as $index => $cell){$cell = trim($cell);$Element = array('name' => 'td','handler' => 'line','text' => $cell,);if (isset($Block['alignments'][$index])){$Element['attributes'] = array('style' => 'text-align: '.$Block['alignments'][$index].';',);}$Elements []= $Element;}$Element = array('name' => 'tr','handler' => 'elements','text' => $Elements,);$Block['element']['text'][1]['text'] []= $Element;return $Block;}}protected function paragraph($Line){$Block = array('element' => array('name' => 'p','text' => $Line['text'],'handler' => 'line',),);return $Block;}protected $InlineTypes = array('"' => array('SpecialCharacter'),'!' => array('Image'),'&' => array('SpecialCharacter'),'*' => array('Emphasis'),':' => array('Url'),'<' => array('UrlTag', 'EmailTag', 'Markup', 'SpecialCharacter'),'>' => array('SpecialCharacter'),'[' => array('Link'),'_' => array('Emphasis'),'`' => array('Code'),'~' => array('Strikethrough'),'\\' => array('EscapeSequence'),);protected $inlineMarkerList = '!"*_&[:<>`~\\';public function line($text){$markup = '';$unexaminedText = $text;$markerPosition = 0;while ($excerpt = strpbrk($unexaminedText, $this->inlineMarkerList)){$marker = $excerpt[0];$markerPosition += strpos($unexaminedText, $marker);$Excerpt = array('text' => $excerpt, 'context' => $text);foreach ($this->InlineTypes[$marker] as $inlineType){$Inline = $this->{'inline'.$inlineType}($Excerpt);if ( ! isset($Inline)){continue;}if (isset($Inline['position']) and $Inline['position'] > $markerPosition){continue;}if ( ! isset($Inline['position'])){$Inline['position'] = $markerPosition;}$unmarkedText = substr($text, 0, $Inline['position']);$markup .= $this->unmarkedText($unmarkedText);$markup .= isset($Inline['markup']) ? $Inline['markup'] : $this->element($Inline['element']);$text = substr($text, $Inline['position'] + $Inline['extent']);$unexaminedText = $text;$markerPosition = 0;continue 2;}$unexaminedText = substr($excerpt, 1);$markerPosition ++;}$markup .= $this->unmarkedText($text);return $markup;}protected function inlineCode($Excerpt){$marker = $Excerpt['text'][0];if (preg_match('/^('.$marker.'+)[ ]*(.+?)[ ]*(?<!'.$marker.')\1(?!'.$marker.')/s', $Excerpt['text'], $matches)){$text = $matches[2];$text = htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');$text = preg_replace("/[ ]*\n/", ' ', $text);return array('extent' => strlen($matches[0]),'element' => array('name' => 'code','text' => $text,),);}}protected function inlineEmailTag($Excerpt){if (strpos($Excerpt['text'], '>') !== false and preg_match('/^<((mailto:)?\S+?@\S+?)>/i', $Excerpt['text'], $matches)){$url = $matches[1];if ( ! isset($matches[2])){$url = 'mailto:' . $url;}return array('extent' => strlen($matches[0]),'element' => array('name' => 'a','text' => $matches[1],'attributes' => array('href' => $url,),),);}}protected function inlineEmphasis($Excerpt){if ( ! isset($Excerpt['text'][1])){return;}$marker = $Excerpt['text'][0];if ($Excerpt['text'][1] === $marker and preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches)){$emphasis = 'strong';}elseif (preg_match($this->EmRegex[$marker], $Excerpt['text'], $matches)){$emphasis = 'em';}else{return;}return array('extent' => strlen($matches[0]),'element' => array('name' => $emphasis,'handler' => 'line','text' => $matches[1],),);}protected function inlineEscapeSequence($Excerpt){if (isset($Excerpt['text'][1]) and in_array($Excerpt['text'][1], $this->specialCharacters)){return array('markup' => $Excerpt['text'][1],'extent' => 2,);}}protected function inlineImage($Excerpt){if ( ! isset($Excerpt['text'][1]) or $Excerpt['text'][1] !== '['){return;}$Excerpt['text']= substr($Excerpt['text'], 1);$Link = $this->inlineLink($Excerpt);if ($Link === null){return;}$Inline = array('extent' => $Link['extent'] + 1,'element' => array('name' => 'img','attributes' => array('src' => $Link['element']['attributes']['href'],'alt' => $Link['element']['text'],),),);$Inline['element']['attributes'] += $Link['element']['attributes'];unset($Inline['element']['attributes']['href']);return $Inline;}protected function inlineLink($Excerpt){$Element = array('name' => 'a','handler' => 'line','text' => null,'attributes' => array('href' => null,'title' => null,),);$extent = 0;$remainder = $Excerpt['text'];if (preg_match('/\[((?:[^][]|(?R))*)\]/', $remainder, $matches)){$Element['text'] = $matches[1];$extent += strlen($matches[0]);$remainder = substr($remainder, $extent);}else{return;}if (preg_match('/^[(]((?:[^ ()]|[(][^ )]+[)])+)(?:[ ]+("[^"]*"|\'[^\']*\'))?[)]/', $remainder, $matches)){$Element['attributes']['href'] = $matches[1];if (isset($matches[2])){$Element['attributes']['title'] = substr($matches[2], 1, - 1);}$extent += strlen($matches[0]);}else{if (preg_match('/^\s*\[(.*?)\]/', $remainder, $matches)){$definition = strlen($matches[1]) ? $matches[1] : $Element['text'];$definition = strtolower($definition);$extent += strlen($matches[0]);}else{$definition = strtolower($Element['text']);}if ( ! isset($this->DefinitionData['Reference'][$definition])){return;}$Definition = $this->DefinitionData['Reference'][$definition];$Element['attributes']['href'] = $Definition['url'];$Element['attributes']['title'] = $Definition['title'];}$Element['attributes']['href'] = str_replace(array('&', '<'), array('&amp;', '&lt;'), $Element['attributes']['href']);return array('extent' => $extent,'element' => $Element,);}protected function inlineMarkup($Excerpt){if ($this->markupEscaped or strpos($Excerpt['text'], '>') === false){return;}if ($Excerpt['text'][1] === '/' and preg_match('/^<\/\w*[ ]*>/s', $Excerpt['text'], $matches)){return array('markup' => $matches[0],'extent' => strlen($matches[0]),);}if ($Excerpt['text'][1] === '!' and preg_match('/^<!---?[^>-](?:-?[^-])*-->/s', $Excerpt['text'], $matches)){return array('markup' => $matches[0],'extent' => strlen($matches[0]),);}if ($Excerpt['text'][1] !== ' ' and preg_match('/^<\w*(?:[ ]*'.$this->regexHtmlAttribute.')*[ ]*\/?>/s', $Excerpt['text'], $matches)){return array('markup' => $matches[0],'extent' => strlen($matches[0]),);}}protected function inlineSpecialCharacter($Excerpt){if ($Excerpt['text'][0] === '&' and ! preg_match('/^&#?\w+;/', $Excerpt['text'])){return array('markup' => '&amp;','extent' => 1,);}$SpecialCharacter = array('>' => 'gt', '<' => 'lt', '"' => 'quot');if (isset($SpecialCharacter[$Excerpt['text'][0]])){return array('markup' => '&'.$SpecialCharacter[$Excerpt['text'][0]].';','extent' => 1,);}}protected function inlineStrikethrough($Excerpt){if ( ! isset($Excerpt['text'][1])){return;}if ($Excerpt['text'][1] === '~' and preg_match('/^~~(?=\S)(.+?)(?<=\S)~~/', $Excerpt['text'], $matches)){return array('extent' => strlen($matches[0]),'element' => array('name' => 'del','text' => $matches[1],'handler' => 'line',),);}}protected function inlineUrl($Excerpt){if ($this->urlsLinked !== true or ! isset($Excerpt['text'][2]) or $Excerpt['text'][2] !== '/'){return;}if (preg_match('/\bhttps?:[\/]{2}[^\s<]+\b\/*/ui', $Excerpt['context'], $matches, PREG_OFFSET_CAPTURE)){$Inline = array('extent' => strlen($matches[0][0]),'position' => $matches[0][1],'element' => array('name' => 'a','text' => $matches[0][0],'attributes' => array('href' => $matches[0][0],),),);return $Inline;}}protected function inlineUrlTag($Excerpt){if (strpos($Excerpt['text'], '>') !== false and preg_match('/^<(\w+:\/{2}[^ >]+)>/i', $Excerpt['text'], $matches)){$url = str_replace(array('&', '<'), array('&amp;', '&lt;'), $matches[1]);return array('extent' => strlen($matches[0]),'element' => array('name' => 'a','text' => $url,'attributes' => array('href' => $url,),),);}}protected function unmarkedText($text){if ($this->breaksEnabled){$text = preg_replace('/[ ]*\n/', "<br />\n", $text);}else{$text = preg_replace('/(?:[ ][ ]+|[ ]*\\\\)\n/', "<br />\n", $text);$text = str_replace(" \n", "\n", $text);}return $text;}protected function element(array $Element){$markup = '<'.$Element['name'];if (isset($Element['attributes'])){foreach ($Element['attributes'] as $name => $value){if ($value === null){continue;}$markup .= ' '.$name.'="'.$value.'"';}}if (isset($Element['text'])){$markup .= '>';if (isset($Element['handler'])){$markup .= $this->{$Element['handler']}($Element['text']);}else{$markup .= $Element['text'];}$markup .= '</'.$Element['name'].'>';}else{$markup .= ' />';}return $markup;}protected function elements(array $Elements){$markup = '';foreach ($Elements as $Element){$markup .= "\n" . $this->element($Element);}$markup .= "\n";return $markup;}protected function li($lines){$markup = $this->lines($lines);$trimmedMarkup = trim($markup);if ( ! in_array('', $lines) and substr($trimmedMarkup, 0, 3) === '<p>'){$markup = $trimmedMarkup;$markup = substr($markup, 3);$position = strpos($markup, "</p>");$markup = substr_replace($markup, '', $position, 4);}return $markup;}function parse($text){$markup = $this->text($text);return $markup;}static function instance($name = 'default'){if (isset(self::$instances[$name])){return self::$instances[$name];}$instance = new self();self::$instances[$name] = $instance;return $instance;}private static $instances = array();protected $DefinitionData;protected $specialCharacters = array('\\', '`', '*', '_', '{', '}', '[', ']', '(', ')', '>', '#', '+', '-', '.', '!', '|');protected $StrongRegex = array('*' => '/^[*]{2}((?:\\\\\*|[^*]|[*][^*]*[*])+?)[*]{2}(?![*])/s','_' => '/^__((?:\\\\_|[^_]|_[^_]*_)+?)__(?!_)/us',);protected $EmRegex = array('*' => '/^[*]((?:\\\\\*|[^*]|[*][*][^*]+?[*][*])+?)[*](?![*])/s','_' => '/^_((?:\\\\_|[^_]|__[^_]*__)+?)_(?!_)\b/us',);protected $regexHtmlAttribute = '[a-zA-Z_:][\w:.-]*(?:\s*=\s*(?:[^"\'=<>`\s]+|"[^"]*"|\'[^\']*\'))?';protected $voidElements = array('area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source',);protected $textLevelElements = array('a', 'br', 'bdo', 'abbr', 'blink', 'nextid', 'acronym', 'basefont','b', 'em', 'big', 'cite', 'small', 'spacer', 'listing','i', 'rp', 'del', 'code','strike', 'marquee','q', 'rt', 'ins', 'font','strong','s', 'tt', 'sub', 'mark','u', 'xm', 'sup', 'nobr','var', 'ruby','wbr', 'span','time',);}function route_index_index () {route_posts_view();}function route_posts_view ($page = 1) {if (!$page) {not_found();}theme('default');$posts = posts_all_paginated(5, $page);layout('posts/index', array('title'=> 'All posts','posts'=> $posts['posts'],'pagination' => $posts['pagination']));}function route_post_view ($post_id = 0) {theme('default');$post = post_by_id($post_id);if (empty($post)) {not_found();}layout('posts/post', array('title' => 'Post ' . $post['title'],'post'  => $post,));}function route_admin_index () {kick_out_user();theme('admin');layout('index', array('title' => 'Howdy, admin!'));}function kick_out_user () {if (!is_admin()) {redirect('admin/login');}}function route_admin_login ($error = '') {theme('admin');view('auth', array('title' => 'Log in, user!','error' => $error));}function route_admin_login_post () {$username = array_get($_POST, 'username');$password = md5(array_get($_POST, 'password'));if (auth_user($username, $password)) {$_SESSION['username'] = $username;$_SESSION['password'] = $password;redirect('admin');}route_admin_login('Wrong username or password!');}function route_admin_logout () {session_destroy();redirect('');}function route_admin_posts_view () {theme('admin');layout('posts/view', array('title' => 'View posts','posts' => db_browse('posts')));}function route_admin_posts_add () {if (is_post() && admin_posts_add($_POST)) {redirect('admin/posts-view');}theme('admin');layout('posts/modify', array('title'  => 'View posts','action' => 'add','form' => array('title' => array('type' => 'input'),'content' => array('type' => 'text'))));}function admin_posts_add (array $input) {return db_insert('posts', $input);}function route_admin_posts_edit ($id = 0) {if (is_post() && admin_posts_edit($id, $_POST)) {redirect('admin/posts-view');}theme('admin');$post = db_find('posts', 'title, content', $id);if (!$post) {not_found();}layout('posts/modify', array('title'  => 'View posts','action' => 'edit','form' => array('title' => array('type'  => 'input','value' => array_get($post, 'title')),'content' => array('type'  => 'text','value' => array_get($post, 'content')))));}function admin_posts_edit ($id, array $input) {return db_update('posts', $input, $id) > 0;}function route_admin_posts_remove ($id = 0) {if (!$id) {not_found();}db_query('DELETE FROM posts WHERE id = ?', array($id));redirect('admin/posts-view');}function posts_all () {return db_select('SELECT id, date, title, content FROM posts ORDER BY id DESC');}function posts_all_paginated ($limit, $page = 1) {$total = posts_count();$pagination = pagination($total, $limit, $page);$limit = $pagination['limit'];$offset = $pagination['offset'];$posts = db_select('SELECT id, date, title, content FROM posts ORDER BY id DESC LIMIT ? OFFSET ?',array($limit, $offset));return compact('posts', 'pagination');}function posts_count () {$count = db_select('SELECT COUNT(*) FROM posts', array(), true);return current($count);}function post_by_id ($id) {return db_select('SELECT id, date, title, content FROM posts WHERE id = ?',array($id), true);}define('BASEPATH', chop(__DIR__, '/'));error_reporting(-1);ini_set('display_errors', 1);date_default_timezone_set('America/Los_Angeles');session_start();auth_user(array_get($_SESSION, 'username'),array_get($_SESSION, 'password'));db_connect(BASEPATH . '/content/db.sqlite');dispatch(array_get($_GET, 'route', ''));