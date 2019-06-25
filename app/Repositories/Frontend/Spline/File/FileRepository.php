<?php 

namespace App\Repositories\Frontend\Spline\File;

use App\Models\File\File;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileRepository extends Repository  {
	/**
	 * Associated Repository Model
	 */
	const MODEL = File::class;

	public function searchByPharmacie($id, $vitrines = null)
	{
		if(!is_client()){
			$with_size = true;
		}else{
			$with_size = false;
		}

		$images = DB::table('files')
					->select('files.path', 'files.related_to->id as pharmacie_id', 'files.related_to->vitrines as vitrine')
					->where('files.related_to->type', 'pharmacie')
					->where('files.related_to->avec_mesure', $with_size)
					->where('files.related_to->id', $id);
		if ($vitrines) {
			$images->whereIn('files.related_to->vitrines', json_decode($vitrines));
		}

		$images = $images->get();
		
		return $images;	
	}

	public function getAttachments($id, $type)
	{
		return File::where('related_to->id', intval($id))
				->where('related_to->type', $type)->get();
	}
	public function store($data)
	{
		$file = new File;

		$file->path	= $data['path'];
		$file->type	= $data['type'];
		$file->related_to	= json_encode($data['related_to']);
		$file->extras = json_encode([]);

		return $this->save($file);
	}

	public function storeMultiple($files, $id, $path, $type)
	{
		foreach ($files as $k => $v) {
			$file_path = Storage::putFile($path, $v);

            $file['path'] = $file_path;
            $file['type'] = 'image';
            $file['related_to'] = [
            	'id' => $id, 
            	'type' => $type,
            	'extension' => $v->extension()
            ];

            $this->store($file);
		}
	}
	
}