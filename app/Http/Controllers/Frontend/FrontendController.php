<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Commande\Commande;
use App\Repositories\Frontend\Spline\Planification\PlanificationRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller
{
    public function __construct(
        PlanificationRepository $planifications
    )
    {
        $this->planifications = $planifications;
    }
	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$user = Auth::user();
		$commandes = [];
		if (is_client()) {
			$commandes = Commande::where('client_id', $user->collaborateur->client_id)
    		->where('status', 'done')
    		->orderBy('extras->history->done', 'desc')
    		->take(10)->get();
		}

		$images_pose = $this->planifications->getFinishedPoseImages();

		return view('frontend.index', [
			'commandes'   => $commandes,
			'images_pose' => $images_pose
		]);
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function macros()
	{
		return view('frontend.macros');
	}
}
