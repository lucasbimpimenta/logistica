<?php


class Util
{
	public static function RetornaSimNao($valor)
	{
		if($valor === true || $valor === 'S')
			return 'Sim';

		if($valor === false || $valor === 'N')
			return 'Não';
	}

	public static function MesesBR($mes_numero, $abreviar=false)
	{
		switch($mes_numero)
		{
			case 1: return (!$abreviar) ? "Janeiro" : 'Jan'; break;
			case 2: return (!$abreviar) ?"Fevereiro" : 'Fev'; break;
			case 3: return (!$abreviar) ?"Março" : 'Mar'; break;
			case 4: return (!$abreviar) ?"Abril" : 'Abr'; break;
			case 5: return (!$abreviar) ?"Maio" : 'Mai'; break;
			case 6: return (!$abreviar) ?"Junho" : 'Jun'; break;
			case 7: return (!$abreviar) ?"Julho" : 'Jul'; break;
			case 8: return (!$abreviar) ?"Agosto" : 'Ago'; break;
			case 9: return (!$abreviar) ?"Setembro" : 'Set'; break;
			case 10: return (!$abreviar) ?"Outubro" : 'Out'; break;
			case 11: return (!$abreviar) ?"Novembro" : 'Nov'; break;
			case 12: return (!$abreviar) ?"Dezembro" : 'Dez'; break;
		}
	}

	public static function GetIP() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public static function formatDinheiro($valor)
	{
		return 'R$ ' . number_format($valor,2,',','.');
	}

	public static function numeroParaSQL($valor)
	{
		$valor = preg_replace("/[^0-9.,]/", "", $valor);
		$valor = str_ireplace('.','', $valor);
		$valor = str_ireplace(',','.', $valor);
		return trim($valor);
	}

	public static function numeroDoSQL($valor)
	{
		return number_format($valor,2,',','.');
	}

	public static function objetoParaArray($object) {
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }
        return $array;
    }
}
?>
