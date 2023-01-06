<?php

namespace App\Interfaces\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

interface PostControllerInterface
{
    /**
     * @OA\Get(path="/bookings?page={page}&limit={limit}&orderBy={orderBy}&sortedBy={sortedBy}&include={include}&search={search}&searchJoin={searchJoin}",
     *   tags={"Booking"},
     *   summary="Get Booking list information",
     *   description="Fetch bookings data for current logged in.",
     *   operationId="getBookings",
     *   @OA\Response(
     *       response=200,
     *       description="Bookings response",
     *       @OA\JsonContent(ref="#/components/schemas/Booking")
     *   ),
     *   @OA\Response(response=400, description="You are not logged in."),
     *   @OA\Response(response=404, description="Booking not found"),
     *   @OA\Parameter(
     *     name="page",
     *     in="path",
     *     description="Page number",
     *     required=false,
     *     @OA\Schema(
     *         type="integer",
     *         format="int32",
     *         minimum=1.0,
     *         default=1
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="limit",
     *     in="path",
     *     description="Page limit",
     *     required=false,
     *     @OA\Schema(
     *         type="integer",
     *         format="int32",
     *         minimum=1.0,
     *         default=20
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="orderBy",
     *     in="path",
     *     description="Order by",
     *     required=false,
     *     @OA\Schema(
     *         type="string",
     *         default="created_at"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="sortedBy",
     *     in="path",
     *     description="Sorted by",
     *     required=false,
     *     @OA\Schema(
     *         type="string",
     *         default="asc"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="include",
     *     in="path",
     *     description="The relationship that need to be fetch",
     *     required=false,
     *     @OA\Schema(
     *         type="array",
     *         default="",
     *         @OA\Items(
     *             type="string"
     *         )
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="search",
     *     in="path",
     *     description="Search with keyword",
     *     required=false,
     *     allowEmptyValue=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="searchJoin",
     *     in="path",
     *     description="Search Join",
     *     required=false,
     *     @OA\Schema(
     *         type="string",
     *         default="or"
     *     )
     *   ),
     * )
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse;

    /**
     * @OA\Get(path="/bookings/{id}?include={include}",
     *   tags={"Booking"},
     *   summary="Find Booking by ID",
     *   description="For valid response try string Id as UUID format",
     *   operationId="getBookingById",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID of Booking that needs to be fetched",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="include",
     *     in="path",
     *     description="The relationship that needs to be fetched",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Booking")
     *   ),
     *   @OA\Response(response=400, description="Invalid ID supplied"),
     *   @OA\Response(response=404, description="Booking not found")
     * )
     *
     * @param  string  $id
     * @param  Request  $request
     * @return JsonResponse
     */
    public function show(Request $request, string $id): JsonResponse;

    /**
     * @OA\Post(path="/bookings",
     *   tags={"Booking"},
     *   summary="Create Booking",
     *   description="This can only be done by the logged in user.",
     *   operationId="createBooking",
     *   @OA\RequestBody(
     *       required=true,
     *       description="Created Booking object",
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(ref="#/components/schemas/BookingCreate")
     *       ),
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(ref="#/components/schemas/BookingCreate")
     *       )
     *   ),
     *   @OA\Response(
     *       response=200,
     *       description="Bookings response",
     *       @OA\JsonContent(ref="#/components/schemas/Success")
     *   ),
     *   @OA\Response(response="default", description="successful operation")
     * )
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse;

    /**
     * @OA\Put(path="/bookings/{id}",
     *   tags={"Booking"},
     *   summary="Update Booking by ID",
     *   description="For valid response try string Id as UUID format",
     *   operationId="BookingUpdate",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID of Booking that needs to be fetched",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Booking")
     *   ),
     *   @OA\Response(response=400, description="Invalid ID supplied"),
     *   @OA\Response(response=404, description="Booking not found"),
     *   @OA\RequestBody(
     *      required=true,
     *      description="Updated Booking object",
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(ref="#/components/schemas/BookingUpdate")
     *      )
     *   )
     * )
     *
     * @param  Request  $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse;

    /**
     * @OA\Delete(path="/bookings/{id}",
     *   tags={"Booking"},
     *   summary="Delete Booking",
     *   description="This can only be done by the logged in Booking.",
     *   operationId="deleteBooking",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The Booking that needs to be deleted",
     *     required=true,
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(response=400, description="Invalid ID supplied"),
     *   @OA\Response(response=404, description="Booking not found")
     * )
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse;
}
