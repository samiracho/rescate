<?php

/**
* This class creates a Tree structure of information for the TreePanel component
* of the ExtJS library
*
* @author Crysfel Villa
* @date 12/18/2009
*
*/
class TreeExtJS{
	private $tree = array();
	private $index = array();
	private $cont = 0;
	
	/**
	* 	This method inserts a node to the Tree, the child param may contain an
	* 	"id" property that will be use as a "key", if the child param doesn't contains
	*	an "id" property a generated "key" is given to the node.
	*
	*	@child the node to insert
	*	@parentKey(optional) The parent key where the node will be inserted, if null 
	*	the node is inserted in the root of the Tree
	*/
	public function addChild($child,$parentKey = null){
		$key = isset($child["id"])?$child["id"]:'item_'.$this->cont;
		$child["leaf"] = true;
		$child["allowDrag"] = true;
		if($this->containsKey($parentKey)){
			//added to the existing node
			$this->index[$key] =& $child;
			$parent =& $this->index[$parentKey];
			if(isset($parent["children"])){
				$parent["children"][] =& $child;
			}else{
				$parent["leaf"] = false;
				// para que salgan collapsed
				$parent["expanded"] = false;
				$parent["allowDrag"] = false;
				$parent["children"] = array();
				$parent["children"][] =& $child;
			}
		}else{
			//added to the root
			$this->index[$key] =& $child;
			$this->tree[] =& $child;
		}
		$this->cont++;
	}
	
	/**
	*	Return a node by the given key
	*	@key
	*/
	public function getNode($key){
		return $this->index[key];
	}
	
	public function getTree()
	{
		return $this->tree;
	}
	
	/**
	*	@TODO Remove the node from the Tree
	*	@key
	*/
	public function removeNode($key){
		//unset($this->index[key]);
	}
	
	/**
	*	Check if exist a node with the given key
	*/
	public function containsKey($key){
		return isset($this->index[$key]);
	}
	
	/**
	*	Return a representation of the Tree structure in JSON format
	*/
	public function toJson(){
		return json_encode($this->tree);
	}
	
	private static function construirLista($parentId, $menuData,$indent = '') 
	{ 
		$html = '';
		
		if (isset($menuData['parents'][$parentId])) 
		{ 		
			foreach ($menuData['parents'][$parentId] as $itemId) 
			{ 
				$html .= '{"nombre":"' . $indent.' '.$menuData['items'][$itemId]['nombre'].'"'; 
				$html .= ',"id":"' . $itemId. '"},';
				
				// find childitems recursively 
				$html .= TreeExtJS::construirLista($itemId, $menuData,$indent.'--');	
						
			} 
		} 
		return  $html;
	} 
	
	public static function ListaIdentada($consulta, $id, $parentId, $nombre)
	{
		$bd = BD::Instancia();
		$datos = $bd->Ejecutar($consulta);
		$data = array();
		$res = new Comunicacion();
		
		$menuData = array( 
		'items' => array(), 
		'parents' => array() 
		); 

		while ($menuItem = $bd->ObtenerFila($datos)) 
		{ 
			$menuData['items'][$menuItem[$id]] = $menuItem; 
			$menuData['items'][$menuItem[$id]]['nombre'] = $menuItem[$nombre]; 
			$menuData['parents'][$menuItem[$parentId]][] = $menuItem[$id]; 
		} 
		
		
		$resultado = TreeExtJS::construirLista(0, $menuData);	
		
		if(strlen($resultado)>0) 
		{
			return '{"data":[{"id":"0","nombre":"Categoría Principal"},'.substr($resultado,0,-1).']}';
		}
		else return '{"data":[{"id":"0","nombre":"Categoría Principal"}]}';
	}
}

?>