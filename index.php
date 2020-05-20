<?php
require "./ModulKu.php";
$ModulKu = new ModulKu;
function headers()
{
    $headers = array();
    $headers[] = 'Authority: www.midasbuy.com';
    $headers[] = 'Accept: /';
    $headers[] = 'X-Requested-With: XMLHttpRequest';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.70 Safari/537.36';
    $headers[] = 'Sec-Fetch-Site: same-origin';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Referer: https://www.midasbuy.com/midasbuy/id/buy?appid=1450015065';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7';
    return $headers;
}   
function get_token_and_cookie()
{
    global $ModulKu;
    $req = $ModulKu->cURL("https://www.midasbuy.com/midasbuy/id/buy?appid=1450015065");
    $cookie = $ModulKu->fetch_cookies($req[0]);
    preg_match('#eyJ(.*?)";#si', $req[1], $token);
    while(!$token){
        $req = $ModulKu->cURL("https://www.midasbuy.com/midasbuy/id/buy?appid=1450015065");
        preg_match('#eyJ(.*?)";#si', $req[1], $token);
    }
    return array("token" => "eyJ".$token[1], "cookies" => $cookie);
}
function get_name($char_id){
    global $ModulKu;
    $info = get_token_and_cookie();
    $headers = headers();
    $headers[] = 'Cookie: UUID='.$info['cookies']['UUID'].'; shopcode=midasbuy; country=id; _ga=GA1.2.1985163670.1572711725; _gid=GA1.2.52058574.1572711725; _gat_gtag_UA_21773189_2=1';
    $req = $ModulKu->cURL("https://www.midasbuy.com/interface/getCharac?ctoken=".$info['token']."&appid=1450015065&currency_type=IDR&country=ID&midasbuyArea=SouthEastAsia&sc=&from=&task_token=&pf=mds_hkweb_pc-v2-android-midasweb-midasbuy&zoneid=1&_id=0.8568872528618487&shopcode=midasbuy&cgi_extend=&buyType=save&openid=".$char_id."");
    $decode = json_decode($req[1], true);
    if(isset($decode['info']['charac_name'])){
        return $decode['info']['charac_name'];
    }else{
        return "ID Not found.";
    }
}
if(isset($_POST['id'])){
    echo "Name : ".get_name(trim($_POST['id']));
}else{
?>
<html>
    <head>
        <title>Check ID PUBG</title>
    </head>
    <body>
        <form method="POST" action="">
            <input type="number" name="id" placeholder="521589**">
            <button type="submit">Cek</button>
        </form>
    </body>
</html>
<?php
}
?>
