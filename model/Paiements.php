<?php
class Paiements{

    //attributes
    private $_id;
	private $_montant;
	private $_dateOperation;
	private $_idAffaire;
	
    //le constructeur
    public function __construct($data){
        $this->hydrate($data);
    }
    
    //la focntion hydrate sert à attribuer les valeurs en utilisant les setters d'une façon dynamique!
    public function hydrate($data){
        foreach ($data as $key => $value){
            $method = 'set'.ucfirst($key);
            
            if (method_exists($this, $method)){
                $this->$method($value);
            }
        }
    }
    
    //setters
    public function setId($id){
        $this->_id = $id;
    }
	
	public function setMontant($montant){
		$this->_montant = $montant;
	}
	
	public function setDateOperation($dateOperation){
		$this->_dateOperation = $dateOperation;
	}
	
	public function setIdAffaire($idAffaire){
		$this->_idAffaire = $idAffaire;
	}
	
    //getters
    
    public function id(){
        return $this->_id;
    }
    
	public function nom(){
		return $this->_nom;
	}
	
	public function montant(){
		return $this->_montant;
	}
	
	public function dateOperation(){
		return $this->_dateOperation;
	}
	
	public function idAffaire(){
		return $this->_idAffaire;
	}
	    
}