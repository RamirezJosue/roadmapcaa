<?php 	
	class Usuario{
		private $id;
		private $nombre;
		private $clave;
		private $apellidos;
		private $dni;
		private $centro;
		private $tipo;
		private $accesos;
		private $estado;
		private $id_empresa;
		private $puesto;
		
		public function getId(){
			return $this->id;
		}
		public function setId($id){
			$this->id = $id;
		}
		public function getNombre(){
			return $this->nombre;
		}
		public function setNombre($nombre){
			$this->nombre = $nombre;
		}
		public function getClave(){
			return $this->clave;
		}
		public function setClave($clave){
			$this->clave = $clave;
		}
		public function getApellidos(){
			return $this->apellidos;
		}
		public function setApellidos($apellidos){
			$this->apellidos = $apellidos;
		}			
		public function getDni(){
			return $this->dni;
		}
		public function setDni($dni){
			$this->dni = $dni;
		}	
		public function getCentro(){
			return $this->centro;
		}
		public function setCentro($centro){
			$this->centro = $centro;
		}
		public function getTipo(){
			return $this->tipo;
		}
		public function setTipo($tipo){
			$this->tipo = $tipo;
		}
		public function getAccesos(){
			return $this->accesos;
		}
		public function setAccesos($accesos){
			$this->accesos = $accesos;
		}
		public function getEstado(){
			return $this->estado;
		}
		public function setEstado($estado){
			$this->estado = $estado;
		}
		public function getIdEmpresa(){
			return $this->id_empresa;
		}
		public function setIdEmpresa($id_empresa){
			$this->id_empresa = $id_empresa;
		}
		public function getPuesto(){
			return $this->puesto;
		}
		public function setPuesto($puesto){
			$this->puesto = $puesto;
		}
	}
?>