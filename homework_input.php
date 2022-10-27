<?php

function pontszamito($tomb)
{
    //követelménynek
$erettsegi=0;
$tantargy=0;
$kotelezo=array("magyar nyelv és irodalom","történelem","matematika");
//pontnak
$watchout=false;
$tipus;
//töbletnek
$tipus2;




$pontok=array();
$nyelvvizsga=array();

    //kiírás tesztelés,ellenőrzés érdekében
    foreach($tomb as $kulcs=>$ertek){
        echo "<h4>".$kulcs.":</h4>";

        foreach($ertek as $ertekkulcs=>$ertek2){
            if(is_string($ertekkulcs))
                echo "<b>".$ertekkulcs.":</b> "; //egyetem, kar, szak
            
                if($ertek2 == "ELTE") //Vagy ELTE vagy PPKE
                {
                    $kell=array("biológia","fizika","informatika","kémia","matematika");
                }
                else if($ertek2== "PPKE")
                {
                    $kell=array("angol","francia","német","olasz","orosz","spanyol","történelem");
                }

            if(is_array($ertek2))
            {
                foreach($ertek2 as $kulcs=>$ertek){
                    //Ha a tárgy megtalálható a kötelező tantárgyakból akkor számítsuk bele a követelményekbe
                    if($kulcs == "nev" && in_array($ertek,$kotelezo))
                    {
                        $erettsegi++;
                    }
                    //Ha a választható tárgy megtalálható a tantárgyakból akkor számítsuk bele a követelményekbe
                    if($kulcs == "nev" && in_array($ertek,$kell))
                    {
                        $erettsegi++;
                        $watchout=true;
                    }

                    if($watchout && $kulcs == "tipus")
                    {
                    $tipus=$ertek;
                    }

                    if($watchout && is_numeric(substr($ertek,0,2)) )
                    {
                    $pontok[$tipus] = substr($ertek,0,2);
                    $watchout=false;
                    
                    }

                    //ha szám azaz eredmény és 20% akkor számítsuk bele a követelményekbe
                    if(is_numeric(substr($ertek,0,2)) && substr($ertek,0,2) > 20)
                    {
                        $erettsegi++;
                    }
                    
                    
                    //tantárgyakat megszámolja
                    if($kulcs == "nev")
                    {
                        $tantargy++;
                    }
                    
                    
                    if(strlen($ertek)==2)
                    {
                        $tipus2=$ertek;
                    }
                    if($kulcs == "nyelv")
                    {
                        $nyelvvizsga[$ertek] = $tipus2;
                    }

                        echo "<b>".$kulcs.":</b>";
                        echo $ertek." ";
                    }
                }else
                {
                    echo $ertek2."\t";  //ELTE, IK , Programtervező informatikus
                    
                    
                }
            }  
        }   
        //kiírás vége

        
        //3 alap tantárgy, 1 kötelező, 1 min választható és sikerességük (lényegében *2) - tantárgyak száma azaz 5 vagy a fölötti az megfelel a követelményeknek, tudunk pontot számítani
        if($erettsegi-$tantargy>=5)
        {
            //Alappontok számítása
            if(count($pontok)>2)
            {
                $elsoelem=$pontok[0]; //matek az ELTE IK esetében mindig az 1. tantárgy
                unset($pontok[0]);
                unset($pontok[array_search(min($pontok),$pontok)]);
                
                array_push($pontok,$elsoelem);
            }
            
            $alappontok=2*array_sum($pontok);
            
            //többletpontok számítása
            $tobbletpontok=0;
            //Emelt = 50p
            if(array_key_exists("emelt",$pontok))
            {
            $tobbletpontok += 50;
        }
        
        //nyelvvizsga ismétlődő nyelv vizsgálása
        $szint= array_values($nyelvvizsga);
        
        for ($i=0; $i <count($szint)-1 ; $i++) { 
            if($szint[$i] == $szint[$i+1])
            {
                unset($nyelvvizsga[array_search("B2",$nyelvvizsga)]);
            }
        }
            
        $szint= array_values($nyelvvizsga);

        for ($i=0; $i <count($szint) ; $i++) { 
            if($szint[$i] == "C1")
            {
                $tobbletpontok+=40;
            }elseif($szint[$i] == "B2")
            {
                $tobbletpontok+=28; 
            }
        }

        //nem lehet több 100-nál a többletpont
        if($tobbletpontok>100)
        {
            $tobbletpontok=100;
        }

        //végső összeadás
        $output=$alappontok+$tobbletpontok;
        echo "<h1>".$output."</h1>";


        }else
        {
            echo "<h1>Hiba!</h1>";
        }
        

}





// output: 470 (370 alappont + 100 többletpont)
$exampleData = [
    'valasztott-szak' => [
        'egyetem' => 'ELTE',
        'kar' => 'IK',
        'szak' => 'Programtervező informatikus',
    ],
    'erettsegi-eredmenyek' => [
        [
            'nev' => 'magyar nyelv és irodalom',
            'tipus' => 'közép',
            'eredmeny' => '70%',
        ],
        [
            'nev' => 'történelem',
            'tipus' => 'közép',
            'eredmeny' => '80%',
        ],
        [
            'nev' => 'matematika',
            'tipus' => 'emelt',
            'eredmeny' => '90%',
        ],
        [
            'nev' => 'angol nyelv',
            'tipus' => 'közép',
            'eredmeny' => '94%',
        ],
        [
            'nev' => 'informatika',
            'tipus' => 'közép',
            'eredmeny' => '95%',
        ],
    ],
    'tobbletpontok' => [
        [
            'kategoria' => 'Nyelvvizsga',
            'tipus' => 'B2',
            'nyelv' => 'angol',
        ],
        [
            'kategoria' => 'Nyelvvizsga',
            'tipus' => 'C1',
            'nyelv' => 'német',
        ],
    ],
];



// output: 476 (376 alappont + 100 többletpont)
$exampleData1 = [
    'valasztott-szak' => [
        'egyetem' => 'ELTE',
        'kar' => 'IK',
        'szak' => 'Programtervező informatikus',
    ],
    'erettsegi-eredmenyek' => [
        [
            'nev' => 'magyar nyelv és irodalom',
            'tipus' => 'közép',
            'eredmeny' => '70%',
        ],
        [
            'nev' => 'történelem',
            'tipus' => 'közép',
            'eredmeny' => '80%',
        ],
        [
            'nev' => 'matematika',
            'tipus' => 'emelt',
            'eredmeny' => '90%',
        ],
        [
            'nev' => 'angol nyelv',
            'tipus' => 'közép',
            'eredmeny' => '94%',
        ],
        [
            'nev' => 'informatika',
            'tipus' => 'közép',
            'eredmeny' => '95%',
        ],
        [
            'nev' => 'fizika',
            'tipus' => 'közép',
            'eredmeny' => '98%',
        ],
    ],
    'tobbletpontok' => [
        [
            'kategoria' => 'Nyelvvizsga',
            'tipus' => 'B2',
            'nyelv' => 'angol',
        ],
        [
            'kategoria' => 'Nyelvvizsga',
            'tipus' => 'C1',
            'nyelv' => 'német',
        ],
    ],
];

// output: hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt
$exampleData2 = [
    'valasztott-szak' => [
        'egyetem' => 'ELTE',
        'kar' => 'IK',
        'szak' => 'Programtervező informatikus',
    ],
    'erettsegi-eredmenyek' => [
        [
            'nev' => 'matematika',
            'tipus' => 'emelt',
            'eredmeny' => '90%',
        ],
        [
            'nev' => 'angol nyelv',
            'tipus' => 'közép',
            'eredmeny' => '94%',
        ],
        [
            'nev' => 'informatika',
            'tipus' => 'közép',
            'eredmeny' => '95%',
        ],
    ],
    'tobbletpontok' => [
        [
            'kategoria' => 'Nyelvvizsga',
            'tipus' => 'B2',
            'nyelv' => 'angol',
        ],
        [
            'kategoria' => 'Nyelvvizsga',
            'tipus' => 'C1',
            'nyelv' => 'német',
        ],
    ],
];

// output: hiba, nem lehetséges a pontszámítás a magyar nyelv és irodalom tárgyból elért 20% alatti eredmény miatt
$exampleData3 = [
    'valasztott-szak' => [
        'egyetem' => 'ELTE',
        'kar' => 'IK',
        'szak' => 'Programtervező informatikus',
    ],
    'erettsegi-eredmenyek' => [
        [
            'nev' => 'magyar nyelv és irodalom',
            'tipus' => 'közép',
            'eredmeny' => '15%',
        ],
        [
            'nev' => 'történelem',
            'tipus' => 'közép',
            'eredmeny' => '80%',
        ],
        [
            'nev' => 'matematika',
            'tipus' => 'emelt',
            'eredmeny' => '90%',
        ],
        [
            'nev' => 'angol nyelv',
            'tipus' => 'közép',
            'eredmeny' => '94%',
        ],
        [
            'nev' => 'informatika',
            'tipus' => 'közép',
            'eredmeny' => '95%',
        ],
    ],
    'tobbletpontok' => [
        [
            'kategoria' => 'Nyelvvizsga',
            'tipus' => 'B2',
            'nyelv' => 'angol',
        ],
        [
            'kategoria' => 'Nyelvvizsga',
            'tipus' => 'C1',
            'nyelv' => 'német',
        ],
    ],
];

pontszamito($exampleData);