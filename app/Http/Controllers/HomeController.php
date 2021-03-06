<?php

namespace App\Http\Controllers;

use App\Models\Bible\BibleFileset;
use App\Models\Country\Country;
use App\Models\Country\CountryLanguage;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use App\Models\Bible\Bible;
use App\Helpers\AWS\Bucket;
use App\Models\Resource\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends APIController
{

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = \Auth::user();

		return view('home', compact('user'));
	}

	public function admin()
	{
		$status['updates'] = '';

		return view('dashboard.admin', compact('status'));
	}

	public function passThrough($path1 = null,$path2 = null, Request $request)
	{
		$params = checkParam('params',null,'optional') ?? $_GET;
		if(is_array($params)) {
			$params = implode('&', array_map(function ($v, $k) {
				if($k === 'key') return "key=53355c32fca5f3cac4d7a670d2df2e09";
				if($k === 0) return $v;
				return sprintf("%s=%s", $k, $v);
			}, $params, array_keys($params)));
		}
		$contents = json_decode(file_get_contents('https://dbt.io/'.$path1.'/'.$path2.'?'.$params));
		return response()->json($contents);
	}

	/**
	 * Returns a List of Buckets used by the API
	 *
	 * @OAS\Get(
	 *     path="/api/buckets",
	 *     tags={"Bibles"},
	 *     summary="Returns aws buckets currently being used by the api",
	 *     description="",
	 *     operationId="v4_api.buckets",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v4_api_buckets")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v4_api_buckets")),
	 *         @OAS\MediaType(mediaType="text/x-yaml",      @OAS\Schema(ref="#/components/schemas/v4_api_buckets"))
	 *     )
	 * )
	 *
	 * @OAS\Schema (
	 *     type="object",
	 *     schema="v4_api_buckets",
	 *     description="The aws buckets currently being used by the api",
	 *     title="The buckets response",
	 *     required={"id","organization_id"},
	 *     @OAS\Xml(name="v4_api_buckets"),
	 *     @OAS\Property(property="id",              ref="#/components/schemas/Bucket/properties/id"),
	 *     @OAS\Property(property="organization_id", ref="#/components/schemas/Bucket/properties/organization_id")
	 * )
	 *
	 * @return mixed
	 */
	public function buckets()
	{
		return $this->reply(\App\Models\Organization\Bucket::with('organization')->get());
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function welcome()
	{
		$count['languages']     = Language::count();
		$count['countries']     = Country::count();
		$count['alphabets']     = Alphabet::count();
		$count['organizations'] = Organization::count();
		$count['bibles']        = Bible::count();

		return view('welcome', compact('count'));
	}

	public function stats()
    {
        $count['languages']      = Language::count();
        $count['countries']      = Country::count();
        $count['alphabets']      = Alphabet::count();
        $count['organizations']  = Organization::count();
        $count['bible_filesets'] = BibleFileset::count();
        $count['bibles']         = Bible::count();
        $count['resources']      = Resource::count();

        return $this->reply($count);
    }

	public function versions()
	{
		return $this->reply(["versions" => [2, 4]]);
	}

	/**
	 *
	 * Returns an array of version return types
	 *
	 * @category v2_video_path
	 * @link http://api.bible.build/api/reply - V4 Access
	 * @link https://api.dbp.dev/api/reply?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/api/apiversion",
	 *     tags={"API"},
	 *     summary="Returns version information",
	 *     description="Gives information about return types of the different versions of the APIs",
	 *     operationId="v2_api_versionLatest",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_api_versionLatest")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v2_api_versionLatest"))
	 *     )
	 * )
	 *
	 * @OAS\Schema (
	 *     type="object",
	 *     schema="v2_api_versionLatest",
	 *     description="The return for the api reply",
	 *     title="v2_api_versionLatest",
	 *     @OAS\Xml(name="v2_api_apiReply"),
	 *     @OAS\Property(property="Version",type="string",example="2.0.0"),
	 * )
	 *
	 * @return mixed
	 */
	public function versionLatest()
	{
		$swagger = json_decode(file_get_contents(public_path('swagger.json')));

		return $this->reply(["Version" => $swagger->info->version]);
	}

	/**
	 *
	 * Returns an array of version return types
	 *
	 * @category v2_api_apiReply
	 * @link http://api.bible.build/api/reply - V4 Access
	 * @link https://api.dbp.dev/api/reply?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/api/reply",
	 *     tags={"API"},
	 *     summary="Returns version information",
	 *     description="Gives information about return types of the different versions of the APIs",
	 *     operationId="v2_api_apiReply",
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_api_apiReply")),
	 *         @OAS\MediaType(mediaType="application/xml",  @OAS\Schema(ref="#/components/schemas/v2_api_apiReply"))
	 *     )
	 * )
	 *
	 * @OAS\Schema (
	 *     type="object",
	 *     schema="v2_api_apiReply",
	 *     description="The return for the api reply",
	 *     title="v2_api_apiReply",
	 *     @OAS\Xml(name="v2_api_apiReply"),
     *     example={"json", "jsonp", "html"}
	 * )
	 *
	 * @return mixed
	 */
	public function versionReplyTypes()
	{
		$versionReplies = [
			"2" => ["json", "jsonp", "html"],
			"4" => ["json", "jsonp", "xml", "html"],
		];

		return $this->reply($versionReplies[$this->v]);
	}


	/**
	 *
	 * Returns an array of signed audio urls
	 *
	 * @category v2_library_asset
	 * @link http://api.bible.build/library/asset - V4 Access
	 * @link https://api.dbp.dev/library/asset?key=1234&v=4&pretty - V4 Test Access
	 * @link https://dbp.dev/eng/docs/swagger/gen#/Version_2/v2_library_asset - V4 Test Docs
	 *
	 * @OAS\Get(
	 *     path="/library/asset",
	 *     tags={"Library Catalog"},
	 *     summary="Returns Library File path information",
	 *     description="This call returns the file path information. This information can be used with the response of the locations calls to create a URI to retrieve files.",
	 *     operationId="v2_library_asset",
	 *     @OAS\Parameter(name="dam_id", in="query", description="The DAM ID for which to retrieve file path info.", @OAS\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
	 *     @OAS\Parameter(ref="#/components/parameters/version_number"),
	 *     @OAS\Parameter(ref="#/components/parameters/key"),
	 *     @OAS\Parameter(ref="#/components/parameters/pretty"),
	 *     @OAS\Parameter(ref="#/components/parameters/format"),
	 *     @OAS\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OAS\MediaType(mediaType="application/json", @OAS\Schema(ref="#/components/schemas/v2_library_asset")),
	 *         @OAS\MediaType(mediaType="application/xml", @OAS\Schema(ref="#/components/schemas/v2_library_asset"))
	 *     )
	 * )
	 *
	 * @OAS\Schema (
	 *     type="object",
	 *     schema="v2_library_asset",
	 *     description="v2_library_asset",
	 *     title="v2_library_asset",
	 *     @OAS\Xml(name="v2_library_asset"),
	 *     @OAS\Property(property="server",type="string",example="cloud.faithcomesbyhearing.com"),
	 *     @OAS\Property(property="root_path",type="string",example="/mp3audiobibles2"),
	 *     @OAS\Property(property="protocol",type="string",example="http"),
	 *     @OAS\Property(property="CDN",type="string",example="1"),
	 *     @OAS\Property(property="priority",type="string",example="5"),
	 *     @OAS\Property(property="volume_id",type="string",example="")
	 * )
	 *
	 * @return mixed
	 */
	public function libraryAsset()
	{
		$dam_id = checkParam('dam_id|fileset_id', null, 'optional') ?? "";
		$bucket_id = checkParam('bucket_id', null, 'optional') ?? 'dbp-dev';

		$fileset = BibleFileset::where('id',$dam_id)->orWhere('id',substr($dam_id,0,-4))->orWhere('id',substr($dam_id,0,-2))->where('bucket_id', $bucket_id)->first();
		if(!$fileset) return $this->setStatusCode(404)->replyWithError("The fileset requested could not be found");

		$s3 = Storage::disk('s3_fcbh');
		$client = $s3->getDriver()->getAdapter()->getClient();

		$libraryAsset = [
			[
				"server"    => $bucket_id.'.'.$client->getEndpoint()->getHost(),
				"root_path" => "/audio",
				"protocol"  => $client->getEndpoint()->getScheme(),
				"CDN"       => "0",
				"priority"  => "5",
				"volume_id" => $dam_id,
			],
			[
				"server"    => 'ccdn.bible.build',
				"root_path" => "/audio",
				"protocol"  => $client->getEndpoint()->getScheme(),
				"CDN"       => "0",
				"priority"  => "6",
				"volume_id" => $dam_id,
			],
		];

		return $this->reply($libraryAsset);
	}

	public function signedUrls()
	{
		$filenames = $_GET['filenames'] ?? "";
		$filenames = explode(",", $filenames);
		$signer    = $_GET['signer'] ?? 's3_fcbh';
		$bucket    = $_GET['bucket'] ?? "dbp-dev";
		$expiry    = $_GET['expiry'] ?? 5;
		$urls      = [];

		foreach ($filenames as $filename) {
			$filename                                      = ltrim($filename, "/");
			$paths                                         = explode("/", $filename);
			$urls["urls"][$paths[0]][$paths[1]][$paths[2]] = Bucket::signedUrl($filename, $signer, $bucket, $expiry);
		}

		return $this->reply($urls);
	}

	public function status_dbl()
	{
		// Fetch Current number of DBL scriptures
		// compare to existing equivalents to DBL
		// return any discrepancy as a to do item
		$status_dbl = '';

		return $status_dbl;
	}

	public function status_biblegateway()
	{
		$status_gateway = '';

		return $status_gateway;
	}

}
