<?php

namespace App\Traits;
use DB;
use App\Models\Log;
use GuzzleHttp\Client;
use App\Models\User_group;
use App\Models\Golf_club_condition_meta;
use stdClass;
use Illuminate\Support\Facades\Crypt;
use App\Traits\userSyncer;
use App\Traits\venueSyncer;
use App\Traits\organizerSyncer;
use App\Traits\categorySyncer;
use App\Traits\eventSyncer;


trait wordpressSyncer{
    use userSyncer;
    use venueSyncer;
    use organizerSyncer;
    use categorySyncer;
    use eventSyncer;
	public function SyncWordpressData()
    {
        $this->syncUser();
        $this->syncVenue();
        $this->syncOrganiser();
        $this->syncCategory();
        $this->syncEvent();
    }

	
	
}


