<?php


abstract class Soba {
    protected $tipSobe;
    protected $imaPrivatnoKupatilo;
    protected $imaBalkon;
    protected $brojSobe;
    public function __construct($brojSobe) {
        $this->brojSobe = $brojSobe;
    }
    public function setTipSobe($tipSobe) {
        $this->tipSobe = $tipSobe;
    }
    public function getTipSobe() {
        return $this->tipSobe;
    }
    public function setImaPrivatnoKupatilo($imaPrivatnoKupatilo) {
        $this->imaPrivatnoKupatilo = $imaPrivatnoKupatilo ? "Ima" : "Nema";
    }
    public function setImaBalkon($imaBalkon) {
        $this->imaBalkon = $imaBalkon ? "Ima" : "Nema";
    }
}
class Jednokrevetna extends Soba {
    public function __construct($brojSobe) {
        parent::__construct($brojSobe);
        $this->tipSobe = 1;
    }
}
class Dvokrevetna extends Soba {
    public function __construct($brojSobe) {
        parent::__construct($brojSobe);
        $this->tipSobe = 2;
    }
}
class Trokrevetna extends Soba {
    public function __construct($brojSobe) {
        parent::__construct($brojSobe);
        $this->tipSobe = 3;
    }
}
interface RoomFactory {
    public function createRoom($brojSobe): Soba;
}
class JednokrevetnaFactory implements RoomFactory {
    public function createRoom($brojSobe): Soba {
        $jednokrevetna = new Jednokrevetna($brojSobe);
        return $jednokrevetna;
    }
}
class DvokrevetnaFactory implements RoomFactory {
    public function createRoom($brojSobe): Soba {
        $dvokrevetna = new Dvokrevetna($brojSobe);
        return $dvokrevetna;
    }
}
class TrokrevetnaFactory implements RoomFactory {
    public function createRoom($brojSobe): Soba {
        $trokrevetna = new Trokrevetna($brojSobe);
        return $trokrevetna;
    }
}

class Hotel {
    private static $instance = null;
    private $sobe = []; 
    private $slobodneSobe = []; 
    private $users = [];
    private function __construct() {}
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Hotel();
        }
        return self::$instance;
    }
    public function dodajUsera(User $user) {
        $this->users[] = $user;
        
    }
    public function oslobodiSobu($oslobodjenTipSobe) {
        
        foreach ($this->users as $user) {
            $user->obavestenje($oslobodjenTipSobe);
        }
    }

    public function dodajSobu(Soba $soba) {
      
        $this->sobe[] = $soba;
        $tipSobe = $soba->getTipSobe();
       
        if (!isset($this->slobodneSobe[$tipSobe])) {
            $this->slobodneSobe[$tipSobe] = 1;
        } else {
            $this->slobodneSobe[$tipSobe]++;
        }
    }
    public function rezervisiSobu(Soba $soba) {
        $tipSobe = $soba->getTipSobe();
        
        if (isset($this->slobodneSobe[$tipSobe]) && $this->slobodneSobe[$tipSobe] > 0) {
            $this->slobodneSobe[$tipSobe]--;
            echo "Rezervisana je soba tipa $tipSobe.\n";
        } else {
            echo "Žao nam je, nema slobodnih soba tipa $tipSobe.\n";
        }
    }
    public function getSlobodneSobe() {
        return $this->slobodneSobe;
    }

    public function dodajSobuFactory(RoomFactory $factory, $brojSobe) {
        $novaSoba = $factory->createRoom($brojSobe);
        $this->dodajSobu($novaSoba);
    }

    public function iznajmiSobu($tipSobe) {
        if (isset($this->slobodneSobe[$tipSobe]) && $this->slobodneSobe[$tipSobe] > 0) {
        
            foreach ($this->sobe as $soba) {
                if ($soba->getTipSobe() === $tipSobe) {
                    $this->rezervisiSobu($soba); 
                    return;
                }
            }
        } else {
            echo "Nema slobodnih soba tipa $tipSobe.\n";
        }
    }
}

class User {
    private $ime;
    private $prezime;
    private $jmbg;
    private $pretplata;

    public function __construct($ime, $prezime, $jmbg, $pretplata) {
        $this->ime = $ime;
        $this->prezime = $prezime;
        $this->jmbg= $jmbg;
        $this->pretplata = NULL;
    }

    public function pretplatiSeNaTipSobe($tipSobe) {
        $this->pretplata = $tipSobe;
    }
    public function obavestenje($tipSobe) {
        if ($tipSobe === $this->pretplata) {
            echo "Poštovani/a {$this->ime}, oslobodila se soba tipa $tipSobe. Možete je rezervisati.\n";
        }
    }
    
}

$hotel = Hotel::getInstance();
$jednokrevetnaFactory = new JednokrevetnaFactory();
$dvokrevetnaFactory = new DvokrevetnaFactory();
$trokrevetnaFactory = new TrokrevetnaFactory();

$hotel->dodajSobuFactory($jednokrevetnaFactory, 101);
$hotel->dodajSobuFactory($jednokrevetnaFactory, 102);
$hotel->dodajSobuFactory($dvokrevetnaFactory, 201);
$hotel->dodajSobuFactory($trokrevetnaFactory, 301);


$rezerviranaSoba = $jednokrevetnaFactory->createRoom(101);
$hotel->rezervisiSobu($rezerviranaSoba);

$slobodneSobe = $hotel->getSlobodneSobe();
foreach ($slobodneSobe as $tipSobe => $brojSlobodnih) {
    echo "Tip sobe: $tipSobe, Slobodnih soba: $brojSlobodnih\n";
}


$hotel->iznajmiSobu(Jednokrevetna::class); 
$hotel->iznajmiSobu(Dvokrevetna::class); 
$hotel->iznajmiSobu(Trokrevetna::class); 

$korisnik1 = new User("Marko", "Markovic", "1234567890123", "ima");
$korisnik2 = new USer("Ana", "Anic", "9876543210987", "ima");
$korisnik1->pretplatiSeNaTipSobe(1); 
$korisnik2->pretplatiSeNaTipSobe(2); 
$hotel->dodajUsera($korisnik1);
$hotel->dodajUSera($korisnik2);

$hotel->oslobodiSobu(1); 
$hotel->oslobodiSobu(3); 


















