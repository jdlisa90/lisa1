
<?php
/* session_start();
try {$bdd = new PDO('mysql:host=localhost;dbname=lisa90;charset=utf8', 'lisa90', 'jlpmjg90',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ));}
catch(Exception $e) {exit(json_encode(array("result"=>'<b>Exception at line '.$e->getLine() .' :</b> '. $e->getMessage(),"res"=>0)));} */
// ne sont pas nécessaires dans un include (le seraient dans une requête ajax)

// l'élément parent est : <div class="col ps-0 ms-0" id="content" style="min-height:80vh;">, qui applique une bordure fine en bas de ses enfants directs 'row' ou 'col'

$content="<div class='row justify-content-center'>
<div class='col pt-4 pb-4 flex-grow-0'>"; // colonne centrée
$link="accueil1-entites_paroisse-ent-";
$content.="<h4>Situation des paroisses à la veille de la Révolution</h4><img src='../img/cartes/paroissesSIG_logo.png' usemap='#paroisse-map'>
<map name='paroisse-map'>
    <area target='' alt='Lepuix' title='Lepuix' href='".$link."32.html' coords='173,34,211,130,132,228,70,169,85,145' shape='poly'>
    <area target='' alt='Giromagy' title='Giromagy' href='".$link."28.html' coords='57,197,34,244,115,252,141,282,176,278,183,264,173,213,129,238' shape='poly'>
    <area target='' alt='Giromagy' title='Giromagy' href='".$link."28.html' coords='227,154,235,202,278,201,302,150,235,132' shape='poly'>
    <area target='' alt='Rougegoutte' title='Rougegoutte' href='".$link."45.html' coords='209,166,190,202,194,293,270,365,246,382,237,415,282,436,286,390,294,326,291,249,279,213,225,220' shape='poly'>
    <area target='' alt='Etueffont' title='Etueffont' href='".$link."20.html' coords='296,231,303,276,305,359,345,344,388,291,349,237' shape='poly'>
    <area target='' alt='Rougemont-le-Château' title='Rougemont-le-Château' href='".$link."46.html' coords='360,160,365,225,403,286,428,316,534,254,529,229,453,215' shape='poly'>
    <area target='' alt='Anjoutey' title='Anjoutey' href='".$link."2.html' coords='399,298,354,359,302,366,307,381,356,389,408,379,410,316' shape='poly'>
    <area target='' alt='St-Germain-le-Châtelet' title='St-Germain-le-Châtelet' href='".$link."49.html' coords='419,326,422,372,447,426,458,420,441,383,460,368' shape='poly'>
    <area target='' alt='Felon' title='Felon' href='".$link."25.html' coords='434,330,468,363,503,343,482,314' shape='poly'>
    <area target='' alt='Lachapelle-sous-Rougemont' title='Lachapelle-sous-Rougemont' href='".$link."34.html' coords='572,281,500,317,514,335,558,334' shape='poly'>
    <area target='' alt='Anjoutey' title='Anjoutey' href='".$link."2.html' coords='315,144,290,213,341,223,342,153' shape='poly'>
    <area target='' alt='Angeot' title='Angeot' href='".$link."1.html' coords='545,259,487,300,495,307,563,277' shape='poly'>
    <area target='' alt='Angeot' title='Angeot' href='".$link."1.html' coords='510,349,462,384,482,393,579,370,560,345,536,344' shape='poly'>
    <area target='' alt='St-Cosme (68)' title='St-Cosme (68)' href='".$link."47.html' coords='598,387,609,401,594,425,613,424,654,399' shape='poly'>
    <area target='' alt='Vauthiermont' title='Vauthiermont' href='".$link."52.html' coords='535,396,524,425,555,423,556,435,580,426,598,400,579,380' shape='poly'>
    <area target='' alt='Larivière' title='Larivière' href='".$link."31.html' coords='451,392,474,425,462,441,533,438,547,432,517,431,522,393,486,399' shape='poly'>
    <area target='' alt='Auxelles-Bas' title='Auxelles-Bas' href='".$link."3.html' coords='36,268,54,331,129,300,117,262' shape='poly'>
    <area target='' alt='Chaux' title='Chaux' href='".$link."13.html' coords='133,306,178,372,253,367,231,331,190,320,176,289' shape='poly'>
    <area target='' alt='Lachapelle-sous-Chaux' title='Lachapelle-sous-Chaux' href='".$link."33.html' coords='63,341,82,402,115,402,176,447,219,410,233,378,169,385,127,315' shape='poly'>
    <area target='' alt='Evette' title='Evette' href='".$link."21.html' coords='79,411,79,473,102,520,115,520,107,503,133,472,173,461,167,448,113,408,98,411' shape='poly'>
    <area target='' alt='Valdoie' title='Valdoie' href='".$link."51.html' coords='217,419,186,455,196,478,227,481,245,463,245,437' shape='poly'>
    <area target='' alt='Belfort' title='Belfort' href='".$link."7.html' coords='184,465,133,481,117,509,150,507,167,524,184,528,225,596,289,538,286,512,302,494,279,446,252,442,256,475,236,489,187,489' shape='poly'>
    <area target='' alt='Phaffans' title='Phaffans' href='".$link."41.html' coords='302,399,292,450,319,489,303,514,320,506,347,517,352,538,454,539,460,525,455,442,435,442,418,389' shape='poly'>
    <area target='' alt='Fontaine' title='Fontaine' href='".$link."27.html' coords='462,456,471,561,510,550,542,556,555,461,533,460,523,448' shape='poly'>
    <area target='' alt='Reppe' title='Reppe' href='".$link."44.html' coords='540,448,559,454,559,495,590,492,582,464,599,440,583,431' shape='poly'>
    <area target='' alt='Essert' title='Essert' href='".$link."19.html' coords='114,531,130,561,114,584,139,597,173,590,188,563,178,534,152,533,143,516' shape='poly'>
    <area target='' alt='Perouse' title='Perouse' href='".$link."40.html' coords='315,515,301,528,276,565,299,569,307,587,333,555,337,526' shape='poly'>
    <area target='' alt='Buc' title='Buc' href='".$link."10.html' coords='104,593,88,597,87,622,103,634,110,648,119,627,123,600' shape='poly'>
    <area target='' alt='Bavilliers' title='Bavilliers' href='".$link."5.html' coords='195,570,176,596,133,603,115,655,116,661,132,661,198,665,219,601' shape='poly'>
    <area target='' alt='Danjoutin' title='Danjoutin' href='".$link."17.html' coords='288,574,232,607,213,645,258,676,297,656,281,632,302,597' shape='poly'>
    <area target='' alt='Chèvremont' title='Chèvremont' href='".$link."12.html' coords='348,545,318,589,427,612,444,603,449,585,446,543,407,552' shape='poly'>
    <area target='' alt='Petit-Croix' title='Petit-Croix' href='".$link."42.html' coords='454,550,452,601,480,626,493,609,484,578' shape='poly'>
    <area target='' alt='Montreux-Château' title='Montreux-Château' href='".$link."36.html' coords='482,569,500,596,497,616,489,633,488,657,518,641,537,647,532,638,543,629,525,596,535,577,528,563,510,558' shape='poly'>
    <area target='' alt='Montreux-Vieux' title='Montreux-Vieux' href='".$link."56.html' coords='538,567,542,590,535,599,547,624,566,606,577,589,588,579,602,555,598,548,569,572,549,583' shape='poly'>
    <area target='' alt='Montreux-Jeune' title='Montreux-Jeune' href='".$link."37.html' coords='585,591,545,642,571,653,569,677,592,699,605,689,619,693,630,706,644,691,652,684,650,666,687,659,669,641,678,613,673,588,618,601,605,587,597,588' shape='poly'>
    <area target='' alt='Vézelois' title='Vézelois' href='".$link."63.html' coords='307,602,294,630,303,657,295,683,286,687,291,707,349,701,375,717,416,674,409,635,421,614,348,600' shape='poly'>
    <area target='' alt='Novillard' title='Novillard' href='".$link."39.html' coords='447,609,428,620,417,648,422,667,434,666,443,678,444,707,469,681,472,669,480,664,478,631' shape='poly'>
    <area target='' alt='Bermont' title='Bermont' href='".$link."8.html' coords='216,655,193,688,179,739,214,755,229,745,257,768,252,810,299,788,278,763,296,716,277,693,276,685,287,679,291,672,276,674,255,680' shape='poly'>
    <area target='' alt='Châtenois-les-Forges' title='Châtenois-les-Forges' href='".$link."11.html' coords='175,749,154,756,154,771,174,811,200,817,242,807,250,782,232,755,208,760' shape='poly'>
    <area target='' alt='Bourogne' title='Bourogne' href='".$link."9.html' coords='300,719,288,759,311,787,347,802,351,816,388,788,385,770,394,768,404,748,354,712' shape='poly'>
    <area target='' alt='Froidefontaine' title='Froidefontaine' href='".$link."24.html' coords='429,674,385,720,414,747,405,768,438,776,496,766,462,758,432,734,434,721,447,713,435,710,439,689' shape='poly'>
    <area target='' alt='Brebotte' title='Brebotte' href='".$link."6.html' coords='521,652,486,672,462,705,457,723,440,727,458,748,479,717,497,701,553,694,558,681,561,662,549,653' shape='poly'>
    <area target='' alt='Grosne' title='Grosne' href='".$link."29.html' coords='565,688,558,702,500,709,471,750,495,752,505,759,528,751,530,764,523,781,524,791,586,807,606,795,588,769,568,754,587,708' shape='poly'>
    <area target='' alt='Suarce' title='Suarce' href='".$link."50.html' coords='663,672,659,691,644,701,641,716,644,727,639,752,663,802,683,799,737,772,739,740,722,716,710,718,691,664' shape='poly'>
    <area target='' alt='Morvillars' title='Morvillars' href='".$link."38.html' coords='393,777,401,794,355,827,372,855,357,869,410,891,417,875,419,844,438,786' shape='poly'>
    <area target='' alt='Grandvillars' title='Grandvillars' href='".$link."30.html' coords='447,785,426,860,424,879,418,892,428,901,440,891,473,906,516,889,530,865,507,844,550,805,518,800,513,780,523,764' shape='poly'>
    <area target='' alt='Delle' title='Delle' href='".$link."18.html' coords='534,873,522,891,485,913,474,944,497,947,505,953,583,919,587,901,573,878' shape='poly'>
    <area target='' alt='Faverois' title='Faverois' href='".$link."22.html' coords='585,820,586,855,579,876,591,896,621,885,630,863,625,814,613,801' shape='poly'>
    <area target='' alt='Florimont' title='Florimont' href='".$link."26.html' coords='579,752,613,791,630,803,644,862,625,890,594,906,591,921,610,935,686,901,676,879,680,827,660,812,629,759,636,731,630,717,621,711,613,701,603,699,586,725' shape='poly'>
    <area target='' alt='Courtelevant' title='Courtelevant' href='".$link."15.html' coords='671,809,690,825,687,870,696,898,714,902,732,836,772,821,791,810,739,783,715,790' shape='poly'>
    <area target='' alt='Réchésy' title='Réchésy' href='".$link."43.html' coords='738,841,721,903,734,938,764,931,788,911,797,908,814,853,797,815' shape='poly'>
    <area target='' alt='Fêche-l'Église' title='Fêche-l'Église' href='".$link."23.html' coords='427,907,443,982,448,955,463,950,474,915,444,898' shape='poly'>
    <area target='' alt='Dasle (25)' title='Dasle (25)' href='".$link."57.html' coords='312,975,317,1004,310,1037,355,1023,368,1008,355,965,337,963' shape='poly'>
    <area target='' alt='Montbouton' title='Montbouton' href='".$link."35.html' coords='376,1014,363,1023,414,1070,418,1023' shape='poly'>
    <area target='' alt='Boncourt (JU)' title='Boncourt (JU)' href='".$link."54.html' coords='584,928,512,960,519,977,531,1029,541,1035,547,1008,564,1003,579,966,597,960,607,942' shape='poly'>
    <area target='' alt='Courcelles' title='Courcelles' href='".$link."14.html' coords='695,909,616,937,682,950,687,959,725,938,721,912' shape='poly'>
    <area target='' alt='St-Dizier-l'Evêque' title='St-Dizier-l'Evêque' href='".$link."48.html' coords='458,963,449,988,433,990,423,1066,473,1078,487,1086,502,1058,524,1038,516,974,488,949' shape='poly'>
    <area target='' alt='Croix' title='Croix' href='".$link."16.html' coords='416,1077,468,1084,483,1093,476,1122,460,1132,433,1134,420,1116,420,1092' shape='poly'>
    <area target='' alt='Bure (JU)' title='Bure (JU)' href='".$link."55.html' coords='533,1044,515,1055,497,1089,480,1127,513,1173,545,1165,553,1153,594,1135,592,1087,567,1055' shape='poly'>
    <area target='' alt='Banvillars' title='Banvillars' href='".$link."4.html' coords='117,670,137,710,172,728,191,669' shape='poly'>
</map>";
$content.="<br><p><b>Sont représent&eacute;es</b> : les paroisses du d&eacute;partement + les paroisses  dont rel&egrave;vent certaines communes frontali&egrave;res 
+ Montreux-Vieux.</p>

Certains villages du Haut-Rhin ont anciennement relevé de paroisses représentées, avant d'en être détachés (Chavannes-sur-l'Étang, Lutran, Valdieu, Éteimbes) ; ils sont donc, sur 
les périodes anciennes, concernés par nos dépouillements (Montreux-Jeune, Angeot, Saint-Cosme).</p>
Les catholiques de Beaucourt relevaient de la paroisse de Montbouton, et les luthériens de celle de Dasle.</p>
Certaines parties de communautés, ou anciennes communautés, étaient rattachées à d'autres paroisses. Par exemple, le village disparu de Normanvillars, essentiellement 
contenu dans la commune actuelle de Florimont, relevait de la paroisse de Grosne.</p>
L'unique <b>lacune</b> dans les dépouillements de LISA est constituée par les registres paroissiaux de <b>Boncourt</b>, dont relevait une partie du village de Joncherey.
<br>La source, conservée aux Archives Cantonales du Jura, n'est actuellement pas accessible.</p>

<p><a href='article-56.html'><b>Historique</b> des diocèses et paroisses du XVème siècle à la Révolution</a></p>";
$content.="</div>
</div>"; // </ col et row>
?>
