

	<?php 

	//header("Content-type: text/xml");

	/*Creating the xml*/
	$doc = new DOMDocument('1.0');
	$doc->formatOutput = true;
	$root = $doc->createElement('result');
	$root = $doc->appendChild($root);

	$CompanyName="GOOG";


	$pattern1=preg_replace('/\s/','+',$CompanyName);
	$stock_quote="http://query.yahooapis.com/v1/public/yql?q=Select%20Name%2C%20Symbol%2C%20LastTradePriceOnly%2C%20Change%2C%20ChangeinPercent%2C%20PreviousClose%2C%20DaysLow%2C%20DaysHigh%2C%20Open%2C%20YearLow%2C%20YearHigh%2C%20Bid%2C%20Ask%2C%20AverageDailyVolume%2C%20OneyrTargetPrice%2C%20MarketCapitalization%2C%20Volume%2C%20Open%2C%20YearLow%20from%20yahoo.finance.quotes%20where%20symbol%3D%22"
	.$pattern1.
	"%22&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";


	$company_news="http://feeds.finance.yahoo.com/rss/2.0/headline?s="
	.$pattern1.
	"&region=US&lang=en-US";
	$company_image="http://chart.finance.yahoo.com/t?s="
	.$pattern1.
	" &amp;lang=en-US&amp;amp;width=300&amp;height=180 ";

	//echo "---------------------------------\n";
	//echo $company_image;
	//echo "---------------------------------\n";
	$stock_quote_xml=@simplexml_load_file($stock_quote);
	$company_news_xml=@simplexml_load_file($company_news);


	if(number_format((float)$stock_quote_xml->results->quote->LastTradePriceOnly,2,'.',',')==0.0)
	{
		//Error
		//echo "Saving all the document:\n";
		echo $doc->saveXML();
	}
	else
	{

	  /*--------------Processing the Stock Quote Information-----------------------------------*/
		//XML ELEMENT
	  $name = $doc->createElement('Name');
	  $name = $root->appendChild($name);
	  
	  $name_text = $doc->createTextNode($stock_quote_xml->results->quote->Name);
	  $name_text = $name->appendChild($name_text);
	  
	  $symbol = $doc->createElement('Symbol');
	  $symbol = $root->appendChild($symbol);
	  
	  $symbol_text = $doc->createTextNode($stock_quote_xml->results->quote->Symbol);
	  $symbol_text = $symbol->appendChild($symbol_text);
	  
	  $quote = $doc->createElement('Quote');
	  $quote = $root->appendChild($quote);
	  
	  
	  
	 
	  /*Changing for Green and Red Color*/
	  $change=$stock_quote_xml->results->quote->Change;
	  $Change_value=substr($change,1);
	  $change_char=str_split($change);
	  
	  //XML ELEMENTS
	  $changeType = $doc->createElement('ChangeType');
	  $changeType = $quote->appendChild($changeType);
	  
	  $changeType_text = $doc->createTextNode($change_char[0]);
	  $changeType_text = $changeType->appendChild($changeType_text);
	  
	  $change = $doc->createElement('Change');
	  $change = $quote->appendChild($change);
	  
	  $change_text = $doc->createTextNode(number_format((float)$Change_value,2,'.',','));
	  $change_text = $change->appendChild($change_text);
	  
	  
	  $Change_in_percent_value=substr($stock_quote_xml->results->quote->ChangeinPercent,1);
	  
	  
	  //XML ELEMENTS
	  $changePercent = $doc->createElement('ChangeInPercent');
	  $changePercent = $quote->appendChild($changePercent);
	  
	  $changePercent_text = $doc->createTextNode( $Change_in_percent_value);
	  $changePercent_text = $changePercent->appendChild($changePercent_text);
	  
	  $lastTrade = $doc->createElement('LastTradePriceOnly');
	  $lastTrade = $quote->appendChild($lastTrade);
	  
	  $lastTrade_text = $doc->createTextNode(number_format((float)$stock_quote_xml->results->quote->LastTradePriceOnly,2,'.',','));
	  $lastTrade_text = $lastTrade->appendChild($lastTrade_text);
	  
	  
	  
	  
	  //XML ELEMENTS
	  $prevClose = $doc->createElement('PreviousClose');
	  $prevClose = $quote->appendChild($prevClose);
	  $prevClose_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->PreviousClose,2,'.',','));
	  $prevClose_text = $prevClose->appendChild($prevClose_text);
	  
	  
	  //XML ELEMENTS
	  
	  $daysLow = $doc->createElement('DaysLow');
	  $daysLow = $quote->appendChild($daysLow);
	  
	  $daysLow_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->DaysLow,2,'.',','));
	  $daysLow_text = $daysLow->appendChild($daysLow_text);
	  
	  $daysHigh = $doc->createElement('DaysHigh');
	  $daysHigh = $quote->appendChild($daysHigh);
	  
	  $daysHigh_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->DaysHigh,2,'.',','));
	  $daysHigh_text = $daysHigh->appendChild($daysHigh_text);
	  
	  
	  
	  //XML ELEMENTS
	  $open = $doc->createElement('Open');
	  $open = $quote->appendChild($open);
	  
	  $open_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->Open,2,'.',','));
	  $open_text = $open->appendChild($open_text);
	  
	  
	  //XML ELEMENTS
	  $yearLow = $doc->createElement('YearLow');
	  $yearLow = $quote->appendChild($yearLow);
	  
	  $yearLow_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->YearLow,2,'.',','));
	  $yearLow_text = $yearLow->appendChild($yearLow_text);
	  
	  $yearHigh = $doc->createElement('YearHigh');
	  $yearHigh = $quote->appendChild($yearHigh);
	  
	  $yearHigh_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->YearHigh,2,'.',','));
	  $yearHigh_text = $yearHigh->appendChild($yearHigh_text);
	  
	  
	  //XML ELEMENTS
	  $bid = $doc->createElement('Bid');
	  $bid = $quote->appendChild($bid);
	  
	  $bid_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->Bid,2,'.',','));
	  $bid_text = $bid->appendChild($bid_text);
	  
	  
	  //XML ELEMENTS
	  $volume = $doc->createElement('Volume');
	  $volume= $quote->appendChild($volume);
	  
	  $volume_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->Volume,2,'.',','));
	  $volume_text = $volume->appendChild($volume_text);
	  

	  //XML ELEMENTS
	  $ask = $doc->createElement('Ask');
	  $ask= $quote->appendChild($ask);
	  
	  $ask_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->Ask,2,'.',','));
	  $ask_text = $ask->appendChild($ask_text);
	  
	  
	  //XML ELEMENTS
	  $avgDV = $doc->createElement('AverageDailyVolume');
	  $avgDV= $quote->appendChild($avgDV);
	  
	  $avgDV_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->AverageDailyVolume,2,'.',','));
	  $avgDV_text = $avgDV->appendChild($avgDV_text);
	  
	  
	  //XML ELEMENTS
	  $oYTP = $doc->createElement('OneYearTargetPrice');
	  $oYTP= $quote->appendChild($oYTP);
	  
	  $oYTP_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->OneyrTargetPrice,2,'.',','));
	  $oYTP_text = $oYTP->appendChild($oYTP_text);
	  

	   //XML ELEMENTS
	  $marketCap = $doc->createElement('MarketCapitalization');
	  $marketCap= $quote->appendChild($marketCap);
	  
	  $marketCap_text = $doc->createTextNode( $stock_quote_xml->results->quote->MarketCapitalization);
	  $marketCap_text = $marketCap->appendChild($marketCap_text);
	  
	  
	  
	  /*-----------------------------Processing Company News Information---------------------------------------------------*/
	  
	   //XML ELEMENTS
	  $news = $doc->createElement('News');
	  $news = $root->appendChild($news);
	  
	   
	  
	  foreach($company_news_xml->channel->children() as $item)
	  {
	   if($item->getName()== 'item')
	   {
			//XML ELEMENTS
			$item_node = $doc->createElement('Item');
			$item_node = $news->appendChild($item_node);
			
			
			$childNodes=$item->children();
			foreach($childNodes as $cch)
			{
				if($cch->getName()=='title')
				{
					$title=$cch;	
				}
				
				if($cch->getName()=='link')
				{
					$link=$cch;	
				}	
			}
			 
			 $title_elem = $doc->createElement('Title');
			 $title_elem= $item_node->appendChild($title_elem);
	  
			 $title_text = $doc->createTextNode( $title);
			 $title_text = $title_elem->appendChild($title_text);
			 
			 $link_elem = $doc->createElement('Link');
			 $link_elem= $item_node->appendChild($link_elem);
	  
			 $link_text = $doc->createTextNode( $link);
			 $link_text = $link_elem->appendChild($link_text);
			 }
			 }
		//XML ELEMENTS
		$image_url = $doc->createElement('StockChartImageURL');
		$image_url = $root->appendChild($image_url);
	  
		$image_url_text = $doc->createTextNode($company_image);
		$image_url_text = $image_url->appendChild($image_url_text);
	 
		echo $doc->saveXML();
		//echo $doc;
	}
	 
	?>


