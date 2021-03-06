<?php

namespace App\Transformers\V2\LibraryCatalog;

use League\Fractal\TransformerAbstract;

class LibraryMetadataTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($bible_fileset)
    {
	        $output = [
		        "dam_id"         => $bible_fileset->v2_id,
		        "fileset_id"     => $bible_fileset->id,
		        "mark"           => $bible_fileset->copyright->copyright,
		        "volume_summary" => $bible_fileset->copyright->copyright_description,
		        "font_copyright" => null,
		        "font_url"       => null
	        ];
	    $organization = @$bible_fileset->copyright->organizations->first();
	    if($organization) {
		    $output["organization"] = [
			    'organization_id'       => $organization->id,
			    'organization'          => $organization->name,
			    'organization_english'  => $organization->name,
			    'organization_role'     => $bible_fileset->copyright->role->roleTitle->name,
			    'organization_url'      => $organization->url_website,
			    'organization_donation' => $organization->url_donate,
			    'organization_address'  => $organization->address,
			    'organization_address2' => $organization->address2,
			    'organization_city'     => $organization->city,
			    'organization_state'    => $organization->state,
			    'organization_country'  => $organization->country,
			    'organization_zip'      => $organization->zip,
			    'organization_phone'    => $organization->phone,
		    ];
	    }
	        return $output;
    }
}
