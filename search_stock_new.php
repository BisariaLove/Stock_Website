<html>
<head>
<style>
.input_box{
	border-style:solid;
	padding:3px;
	border-width: 2px;
}
</style>

<script type="text/javascript">
function check()
{
var url= document.getElementById("CName").value;
if(url.length<1)
{
	alert("Please Enter a Company Symbol");
	return false;
}
	return true;
}
</script>


</head>
<body>
<table>

<form  onsubmit=check() method="POST" >
<center>
<b><font size="6">Market Stock Research</font></b>
</center>

<table align= "center">

<tr>
	<td width='100%'>
	<div class='input_box'>
		
	Company Symbol:<Input type='text' name='CompanyName' id='CName' style="width:350px"/>
	<input type="submit"  name="submit" id='search' value='Search'/>
	<br/>
	Example: GOOG, MSFT,YHOO,FB, AAPL,...etc

	</div>
	</td>
</tr>


</table>

</form>
</div>


<?php 
if(isset($_POST["submit"]))
{

$cname=$_POST["CompanyName"];
$print_str="";
if(strlen($cname) == 0 )
{
}
else{
header('Content-Type: text/html; charset=ISO-8859-1');

/*Creating the xml*/
$doc = new DOMDocument('1.0');
$doc->formatOutput = true;
$root = $doc->createElement('result');
$root = $doc->appendChild($root);

$CompanyName=$_POST["CompanyName"];

/*if (strlen($CompanyName)<1)
{
	echo "<script type=\'text/javascript\'>alert(\'The CompanyName cannot be Empty\')</script>";
}
else
{
	echo "<H1>The Company Name Entered is:" .$CompanyName."</H1>" ;
}*/
$pattern1=preg_replace('/\s/','+',$CompanyName);
$stock_quote="http://query.yahooapis.com/v1/public/yql?q=Select%20Name%2C%20Symbol%2C%20LastTradePriceOnly%2C%20Change%2C%20ChangeinPercent%2C%20PreviousClose%2C%20DaysLow%2C%20DaysHigh%2C%20Open%2C%20YearLow%2C%20YearHigh%2C%20Bid%2C%20Ask%2C%20AverageDailyVolume%2C%20OneyrTargetPrice%2C%20MarketCapitalization%2C%20Volume%2C%20Open%2C%20YearLow%20from%20yahoo.finance.quotes%20where%20symbol%3D%22$pattern1%22&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";


$company_news="http://feeds.finance.yahoo.com/rss/2.0/headline?s=$pattern1&region=US&lang=en-US";
$stock_quote_xml=simplexml_load_file($stock_quote);
$company_news_xml=simplexml_load_file($company_news);


if(number_format((float)$stock_quote_xml->results->quote->LastTradePriceOnly,2,'.',',')==0.0)
{
echo "<br/><br/><center><b><font size=\"5\">"."STOCK INFORMATION NOT AVAILAIBLE"."</font></b></center>";
}
else
{
	echo"<br><center><b><font size=\"4\">Search Results</font></b></center><br/>";

/*foreach($stock_quote_xml->results->quote->children() as $child)
  {
  echo $child->getName() . ": " . $child . "<br/>";
  }*/
  /*--------------Processing the Stock Quote Information-----------------------------------*/
  echo "<b><font size=\"4\">".$stock_quote_xml->results->quote->Name."(".$stock_quote_xml->results->quote->Symbol.")</font></b>"; 
  
  
  
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
  
  
  
  echo " &nbsp;".number_format((float)$stock_quote_xml->results->quote->LastTradePriceOnly,2,'.',',')."&nbsp;";
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
  
  
  if($change_char[0]=='-')
  { 
	$print_str = "<img src=\" down_r.gif\" ></img>"."<font color=\" red \">".number_format((float)$Change_value,2,'.',',')."(".$Change_in_percent_value.")</font>";
  }
  
  if($change_char[0]=='+')
  {
	$print_str = "<img src=\" up_g.gif\" ></img>"."<font color=\" green \">".number_format((float)$Change_value,2,'.',',')."(".$Change_in_percent_value.")</font>";
  }
  echo $print_str;
  echo "<hr style=\"height:3px;border:none;color:#333;background-color:#333;\">";
  
  
  echo "<table>";
  echo "<tr>";
  echo"<td>Prev Close:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->PreviousClose,2,'.',',')."</td>";
  echo"<td>Day's Range:</td>";
  
  //XML ELEMENTS
  $prevClose = $doc->createElement('PreviousClose');
  $prevClose = $quote->appendChild($prevClose);
  $prevClose_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->PreviousClose,2,'.',','));
  $prevClose_text = $prevClose->appendChild($prevClose_text);
  
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->DaysLow,2,'.',',')."-".number_format((float)$stock_quote_xml->results->quote->DaysHigh,2,'.',',')."</td>";
  echo "</tr>";
  
  //XML ELEMENTS
  
  $daysLow = $doc->createElement('DaysLow');
  $daysLow = $quote->appendChild($daysLow);
  
  $daysLow_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->DaysLow,2,'.',','));
  $daysLow_text = $daysLow->appendChild($daysLow_text);
  
  $daysHigh = $doc->createElement('DaysHigh');
  $daysHigh = $quote->appendChild($daysHigh);
  
  $daysHigh_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->DaysHigh,2,'.',','));
  $daysHigh_text = $daysHigh->appendChild($daysHigh_text);
  
  
  
  echo "<tr>";
  echo"<td>Open:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->Open,2,'.',',')."</td>";
  echo"<td>52wk Range:</td>";
  
  //XML ELEMENTS
  $open = $doc->createElement('Open');
  $open = $quote->appendChild($open);
  
  $open_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->Open,2,'.',','));
  $open_text = $open->appendChild($open_text);
  
  
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->YearLow,2,'.',',')."-".number_format((float)$stock_quote_xml->results->quote->YearHigh,2,'.',',')."</td>";
  
  //XML ELEMENTS
  $yearLow = $doc->createElement('YearLow');
  $yearLow = $quote->appendChild($yearLow);
  
  $yearLow_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->YearLow,2,'.',','));
  $yearLow_text = $yearLow->appendChild($yearLow_text);
  
  $yearHigh = $doc->createElement('YearHigh');
  $yearHigh = $quote->appendChild($yearHigh);
  
  $yearHigh_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->YearHigh,2,'.',','));
  $yearHigh_text = $yearHigh->appendChild($yearHigh_text);
  
  
  echo "</tr>";
  echo "<tr>";
  echo"<td>Bid:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->Bid,2,'.',',')."</td>";
  
  //XML ELEMENTS
  $bid = $doc->createElement('Bid');
  $bid = $quote->appendChild($bid);
  
  $bid_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->Bid,2,'.',','));
  $bid_text = $bid->appendChild($bid_text);
  
  echo"<td>Volume:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->Volume,2,'.',',')."</td>";
  
  //XML ELEMENTS
  $volume = $doc->createElement('Volume');
  $volume= $quote->appendChild($volume);
  
  $volume_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->Volume,2,'.',','));
  $volume_text = $volume->appendChild($volume_text);
  
  echo "</tr>";
  echo "<tr>";
  echo"<td>Ask:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->Ask,2,'.',',')."</td>";
  
  //XML ELEMENTS
  $ask = $doc->createElement('Ask');
  $ask= $quote->appendChild($ask);
  
  $ask_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->Ask,2,'.',','));
  $ask_text = $ask->appendChild($ask_text);
  
  echo"<td>Avg Vol(3m):</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->AverageDailyVolume,2,'.',',')."</td>";
  
  //XML ELEMENTS
  $avgDV = $doc->createElement('AverageDailyVolume');
  $avgDV= $quote->appendChild($avgDV);
  
  $avgDV_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->AverageDailyVolume,2,'.',','));
  $avgDV_text = $avgDV->appendChild($avgDV_text);
  
  echo "</tr>";
  echo "<tr>";
  echo"<td>1y Target Est:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->OneyrTargetPrice,2,'.',',')."</td>";
  
  //XML ELEMENTS
  $oYTP = $doc->createElement('OneYearTargetPrice');
  $oYTP= $quote->appendChild($oYTP);
  
  $oYTP_text = $doc->createTextNode( number_format((float)$stock_quote_xml->results->quote->OneyrTargetPrice,2,'.',','));
  $oYTP_text = $oYTP->appendChild($oYTP_text);
  
  echo"<td>Market Cap:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".$stock_quote_xml->results->quote->MarketCapitalization."</td>";
  
   //XML ELEMENTS
  $marketCap = $doc->createElement('MarketCapitalization');
  $marketCap= $quote->appendChild($marketCap);
  
  $marketCap_text = $doc->createTextNode( $stock_quote_xml->results->quote->MarketCapitalization);
  $marketCap_text = $marketCap->appendChild($marketCap_text);
  
  
  echo "</tr>";
  echo "</table>";

  /*-----------------------------Processing Company News Information---------------------------------------------------*/
  
   //XML ELEMENTS
  $news = $doc->createElement('News');
  $news = $root->appendChild($news);
  
   
  echo "<br/>";
  echo "<b><font size=\"4\">News Headlines</font></b>"; 
  echo "<hr style=\"height:3px;border:none;color:#333;background-color:#333;\"/>";
  echo "<ul>";
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
		 echo "<li><a href=\" ".$link."\">".htmlentities($title)."</a></li>";
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
  echo "</ul>";
 } 
 echo "Saving all the document:\n";
 echo $doc->saveXML();
 }
 }
 
?>
</table>
<noscript>
</body>
</html>


