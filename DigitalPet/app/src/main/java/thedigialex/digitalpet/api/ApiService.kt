package thedigialex.digitalpet.api

import retrofit2.Call
import retrofit2.Response
import retrofit2.http.Field
import retrofit2.http.FormUrlEncoded
import retrofit2.http.GET
import retrofit2.http.POST
import thedigialex.digitalpet.model.responses.DigitalMonstersResponse
import thedigialex.digitalpet.model.responses.InventoryItemResponse
import thedigialex.digitalpet.model.responses.ItemResponse
import thedigialex.digitalpet.model.responses.LoginResponse
import thedigialex.digitalpet.model.responses.UserDigitalMonsterResponse

interface ApiService {

    @FormUrlEncoded
    @POST("api/login")
    suspend fun login(@Field("email") email: String, @Field("password") password: String): Response<LoginResponse>

    @FormUrlEncoded
    @POST("api/register")
    suspend fun registerUser(@Field("name") name: String, @Field("email") email: String, @Field("password") password: String): Response<LoginResponse>

    @GET("api/check-token")
    suspend fun checkToken(): Response<LoginResponse>

    @POST("api/logout")
    fun logout(): Call<Void>

    @GET("api/eggs")
    suspend fun getEggs(): Response<DigitalMonstersResponse>

    @FormUrlEncoded
    @POST("api/items")
    suspend fun getItems(@Field("type") type: String): Response<ItemResponse>

    @FormUrlEncoded
    @POST("api/user-digital-monster/create")
    suspend fun createUserDigitalMonster(@Field("digital_monster_id") digitalMonsterId: Int, @Field("name") name: String): Response<UserDigitalMonsterResponse>

    @FormUrlEncoded
    @POST("api/user-digital-monster/save")
    suspend fun saveUserDigitalMonster(
        @Field("id") id: Int,
        @Field("name") name: String?,
        @Field("level") level: Int?,
        @Field("exp") exp: Int?,
        @Field("strength") strength: Int?,
        @Field("agility") agility: Int?,
        @Field("defense") defense: Int?,
        @Field("mind") mind: Int?,
        @Field("hunger") hunger: Int?,
        @Field("exercise") exercise: Int?,
        @Field("clean") clean: Int?,
        @Field("energy") energy: Int?,
        @Field("maxEnergy") maxEnergy: Int?,
        @Field("wins") wins: Int?,
        @Field("losses") losses: Int?,
        @Field("trainings") trainings: Int?,
        @Field("maxTrainings") maxTrainings: Int?,
        @Field("currentEvoPoints") currentEvoPoints: Int?,
        @Field("sleepStartedAt") sleepStartedAt: String?
    ): Response<UserDigitalMonsterResponse>

    @POST("api/user-digital-monster/evolve")
    suspend fun evolve(): Response<UserDigitalMonsterResponse>

    @FormUrlEncoded
    @POST("api/inventory-item/update")
    suspend fun updateInventoryItem(@Field("inventory_id") inventoryId: Int): Response<InventoryItemResponse>
}
