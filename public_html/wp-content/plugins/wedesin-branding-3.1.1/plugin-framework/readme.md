Dokumentace Plugin Frameworku
=============================
**Založení nového pluginu**

git clone DigiEcoMail(později novou plugin template)

po git clone přejmenujeme složku podle nového pluginu, následně otevřeme soubor plugin.php kde přepišme hlavičku komentáře podle nového pluginu.

poté změníme definici" define( 'D1G1\_BRANDING', 'DigiEcoMail' ); " jak hodnotu jak klič následně klič změníme globálně v celém pluginu na novou definici.

všechny ostatní definice se změní automaticky podle první definice napřiklad "D1G1\_PLUGNAME" je pak D1G1\_PLUGNAME\_ + to co nese definice "D1G1\_BRANDING" viz soubor ve frameworku globals.php

poté globálně změníme namespace v souborech z DigiEcoMail na název nového pluginu.

poté mužem na gitu vytvořit novy reporitař a pushnout to tam. nezapomenout smazat puvodní .git složku.

**Aktualizace frameworku ve vyhotoveném pluginu.**

aktualizaci frameworku již v hotovém pluginu provádíme následujícím způsobem.

použijeme příkaz

    git submodule foreach git pull origin master

po stažení gitu je potřeba udelat stejný krok jako při zakladání pluginu .

změnit hlavni definici globálně, a změnit namespace globalně.

**Aktualizace frameworku na git frameworku**.

Změny ve frameworku které chceme implementovat pro všechny pluginy ktery framework použivaji , mužeme provect v jakem koli pluginu a nasledně stači pushnout submodul.

submodul pushneme nasledujicím postupem.

ve složce pluginu vlezeme v consoli do složky frameworku "cd plugin-framework" a následně použijeme tyto příkazy

    git add *
    git commit -m "komentář"
    git push origin HEAD:master

#### Skladaní polí (administrace pluginu)

pole se skládají defakto stejně jako v předešlém frameworku jen s pár změnami.

plugin musí obsahovat ve složce admin/fields soubor d1g1PluginField.php s existující třídou d1g1PluginField a funkci get\_fields\_form

ve funkci je prázdný array($fields) kde podmínkami (pozdeji switch) zvolíme ID formuláře s následně vytvoříme array($fields) a naplníme ho

jako první jsou 3 hodnoty formulářě

    'headline' => 'Nadpis formuláře',
    'description' => 'Popisek formuláře',
    'enctype' => 'Specifikuje typ kódování pro odesílání dat formuláře', // není povinný
    'action => 'akce',

*   `headline`: Nadpis formuláře. Tento text se používá jako nadpis pro celý formulář.
*   `description`: Popisek formuláře. Tento text slouží jako obecný popis nebo instrukce pro uživatele.
*   `enctype`: Specifikuje typ kódování pro odesílání dat formuláře. V tomto případě je prázdný, což znamená, že se používá výchozí hodnota.
*   action: specifikuje či se má zavolat hook pokud existuje, hodnota je důležitá pro další použití

jako další povinné pole je 'sections' , Pole sekcí formuláře. Každá sekce obsahuje pak další klíče a hodnoty.

    'sections' => [],
    

pole sections je pole kde se nachází další pole s jednotlivými sekcemi.

    'sections' => [
           'section' => [
                 'headline' => 'nadpis sekce',
                 'description => 'popisek sekce',
                 'fields => [],
                 ],
         ],
    
    

*   section: tento klič určuje nazev sekce muže se jmenovat jak koly a jeho hodnota je array kde jsou dalši pole
*   section->headline: Nadpis sekce. Tento text se používá jako nadpis pro danou sekci.
*   section->`description`: Popisek sekce. Tento text slouží jako obecný popis nebo instrukce pro uživatele.
*   section->fields : toto pole obsahuje další pole s jednotlivými fieldy.

Fieldy
------

field se zkláda z pole a v poli jsou 4 povinné pole

\[  
'type' => 'typ fieldu', -  
'name' => 'název fieldu',  
'label' => 'label fieldu',  
'saveAs' => 'typ uložení ',

\],

type: určuje typ fieldu (text,editor,select ...)

name: název fieldu pod kterým se field ukláda do databaze.

label: label fieldu (nadpis)

saveAs : způsob uložení , (meta nebo options)

**dale fieldy obsahuji nepovinné pole pro další nastavení nebo validaci**

**Nepovinné pomocné pole k fieldum**

'help\_text' => "pomocný text"

'args' => " array . nastaveni například pro select"

'options' => nastavení pro fieldy

'value' => vychozí hodnota

'css\_class' => css klasa

'options' => další array s nastavením pro field

'width' => half nebo full značí velikost fieldu.

'atts' => html5 atributy

Validace
--------

každý field mužem validovat pomoci pole 'rules'

**_seznam pravidel_**

required, string, email, url, numeric, same, date, file, image, mime, required\_if, size, max, min

*   required
    *   žádné dalši parametry nejsou třeba, pole je jen povinné.
*   string
    *   žádné dalši parametry nejsou třeba, kontrola či zadaná hodnota je string.
*   email
    *   žádné dalši parametry nejsou třeba, kontrola či zadaná hodnota je email.
*   url
    *   žádné dalši parametry nejsou třeba, kontrola či zadana hodnota je url.
*   numeric
    *   žádné dalši parametry nejsou třeba, kontrola či zadana hodnota je url.
*   same
    *   vyžaduje dalši parametr nazev pole. kontroluje či zadaná hodnota je stejná jako zadaný pole
    *   zapis : same:nazev-pole
*   date
    *   žádné dalši parametry nejsou třeba, kontrola či zadana hodnota je datum.
*   image
    *   žádné dalši parametry nejsou třeba, kontrola či zadana hodnota je image.
*   mime
    *   vyžaduje dalši parametry. kontroluje či zadaný soubor je zadaný typ souboru.
    *   zapis : mime:pdf,doc
*   required\_if
    *   vyžaduje dalši parametr nazev pole. požaduje hodnotu pokud je zadaná hodnota v zadanem poli.
    *   zapis: required\_if:nazev-pole
*   size
    *   vyžaduje dalši parametr velikost. kontroluje velikost hodnoty, pro string,number, ale i file.
    *   zapis : size:100
*   max
    *   vyžaduje dalši parametr velikost. kontroluje velikost hodnoty, pro string,number
    *   zapis : max:20
*   min
    *   vyžaduje dalši parametr velikost. kontroluje velikost hodnoty, pro string,number
    *   zapis: min:20

### Jednotlivé fieldy

seznam dostupných fieldu.

*   select
*   checkbox
*   color
*   date
*   file
*   image
*   radio
*   range
*   text
*   url
*   switch
*   textarea
*   editor
*   info\_box
*   warning\_box
*   html

příklad jednotlivých poly pro fieldy, bez povinných poli.

**select**

'args' => \[

'options' => \[

'option1' => 'Option 1',

'option2' => 'Option 2',

\]

\]

\]

**checkbox**

'options' => \[

'checkboxs' => \[

'1' => '1',

'2' => '2',

'3' => '3',

\]

\]

**color**

nemá žádné specifické fieldy

**date**

nemá žádné specifické fieldy

**file**

nemá žádné specifické fieldy

**Image**

nemá žádné specifické fieldy

**radio**

'args' => \[

'options' => \[

'option1' => 'Option 1',

'option2' => 'Option 2',

\]

\]

\]

**range**

U tohoto fieldu je pole rules povinná z důvodu nastavení min a max pro html 5 kde je společné s validaci php.

'rules' => "min:10|max:20",

'options' => \[ - nastavení pro html

'step' => 1 /nastavení stepu pohybu.

'show\_attr' => true/false // $show\_attr == true ? $wrap\_class . ' show-attr' : $wrap\_class;

'unit' => true/false // ? 'val-right-large' : 'val-right'

\]

**text**

nemá žádné specifické fieldy

**url**

nemá žádné specifické fieldy

**switch**

'checked' => 'checked' / defautně zaškrtnuté.

**textarea**

nemá žádné specifické fieldy

**editor**

'args' => \[

'length' => 200 // počet znaku

\]

**info\_box**/**warning\_box**

nemá žádné specifické fieldy

**html**

'custom\_html' => "html" / html kod