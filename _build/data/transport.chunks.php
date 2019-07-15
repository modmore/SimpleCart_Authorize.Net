<?php

$list = array(
	'scAuthorizeNetForm' => 'Used for the Authorize.net payment form embedded into the payment page.',
);

$chunks = array();

$i = 1;
foreach($list as $chunk => $description) {
	
	$modx->log(modX::LOG_LEVEL_INFO, 'Adding chunk '.$chunk.'...');

    // determine chunkfile and get contents
    $chunkFile = $sources['chunks'].strtolower($chunk).'.chunk.tpl';
    if(!file_exists($chunkFile)) { $chunkFile = $sources['chunks'].strtolower($chunk).'.tpl'; }
    $contents = file_exists($chunkFile) ? file_get_contents($chunkFile) : '';

    $chunks[$i]= $modx->newObject('modChunk');
	$chunks[$i]->fromArray(array(
		'id' => 0,
		'name' => $chunk,
		'description' => $description,
		'snippet' => $contents,
		'properties' => '',
        'locked' => true,
	), '', true, true);
	
	$i++;
}

return $chunks;