<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

use App\Models\Organization\Organization;

/**
 * App\Models\Bible\BibleEquivalent
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Bible Equivalent Model stores the connections between the bible IDs and external organizations",
 *     title="BibleEquivalent",
 *     @OAS\Xml(name="BibleEquivalent")
 * )
 *
 */
class BibleEquivalent extends Model
{
    protected $table = "bible_equivalents";
    protected $primaryKey = 'equivalent_id';
    protected $hidden = ['created_at','updated_at','bible_id'];
    protected $fillable = ['bible_id','equivalent_id','organization_id','type','suffix'];
    public $incrementing = false;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Bible/properties/id")
	 * @method static BibleEquivalent whereBibleId($value)
	 * @property string $bible_id
	 *
	*/
    protected $bible_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="equivalent_id",
	 *   type="string",
	 *   description="The equivalent_id",

	 *   maxLength=191,
	 *   example="FreGeneve1669"
	 * )
	 * @method static BibleEquivalent whereEquivalentId($value)
	 * @property string $equivalent_id
	 *
	 */
	protected $equivalent_id;

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Organization/properties/id")
	 * @method static BibleEquivalent whereOrganizationId($value)
	 * @property int $organization_id
	 *
	 */
	protected $organization_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="type",
	 *   type="string",
	 *   description="The type of connection that the equivalent id refers to",

	 *   maxLength=191,
	 *   example="desktop-app"
	 * )
	 * @method static BibleEquivalent whereType($value)
	 * @property string $type
	 *
	 */
	protected $type;

	/**
	 *
	 * @OAS\Property(
	 *   title="site",
	 *   type="string",
	 *   description="The name of the site/organization/app where the equivalent id is based",

	 *   maxLength=191,
	 *   example="eSword"
	 * )
	 * @method static BibleEquivalent whereSite($value)
	 * @property string $site
	 *
	 */
	protected $site;

	/**
	 *
	 * @OAS\Property(
	 *   title="site",
	 *   type="string",
	 *   description="Additional metadata affecting the type of equivalent connection",

	 *   maxLength=191,
	 *   example="Authorized Version with Strong's"
	 * )
	 * @method static BibleEquivalent whereSuffix($value)
	 * @property string $suffix
	 *
	 */
	protected $suffix;

    public function bible()
    {
        return $this->BelongsTo(Bible::class,'bible_id','id');
    }

    public function organization()
    {
        return $this->HasOne(Organization::class,'id','organization_id');
    }

}
