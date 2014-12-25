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
  echo " &nbsp;".number_format((float)$stock_quote_xml->results->quote->LastTradePriceOnly,2,'.',',')."&nbsp;";
  /*Changing for Green and Red Color*/
  $change=$stock_quote_xml->results->quote->Change;
  $Change_value=substr($change,1);
  $change_char=str_split($change);
  
  $Change_in_percent_value=substr($stock_quote_xml->results->quote->ChangeinPercent,1);
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
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->DaysLow,2,'.',',')."-".number_format((float)$stock_quote_xml->results->quote->DaysHigh,2,'.',',')."</td>";
  echo "</tr>";
  echo "<tr>";
  echo"<td>Open:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->Open,2,'.',',')."</td>";
  echo"<td>52wk Range:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->YearLow,2,'.',',')."-".number_format((float)$stock_quote_xml->results->quote->YearHigh,2,'.',',')."</td>";
  echo "</tr>";
  echo "<tr>";
  echo"<td>Bid:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->Bid,2,'.',',')."</td>";
  echo"<td>Volume:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->Volume,2,'.',',')."</td>";
  echo "</tr>";
  echo "<tr>";
  echo"<td>Ask:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->Ask,2,'.',',')."</td>";
  echo"<td>Avg Vol(3m):</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->AverageDailyVolume,2,'.',',')."</td>";
  echo "</tr>";
  echo "<tr>";
  echo"<td>1y Target Est:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".number_format((float)$stock_quote_xml->results->quote->OneyrTargetPrice,2,'.',',')."</td>";
  echo"<td>Market Cap:</td>";
  echo"<td style=\"padding-right: 30px; padding-left: 75px\">".$stock_quote_xml->results->quote->MarketCapitalization."</td>";
  echo "</tr>";
  echo "</table>";

  /*-----------------------------Processing Company News Information---------------------------------------------------*/
  
  echo "<br/>";
  echo "<b><font size=\"4\">News Headlines</font></b>"; 
  echo "<hr style=\"height:3px;border:none;color:#333;background-color:#333;\"/>";
  echo "<ul>";
  foreach($company_news_xml->channel->children() as $item)
  {
   if($item->getName()== 'item')
   {
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
	}
  }
  echo "</ul>";
 } 
 }
 }
?>
</table>
<noscript>
</body>
</html>


