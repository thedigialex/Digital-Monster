package thedigialex.digitalpet.api

import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.Field
import retrofit2.http.FormUrlEncoded
import retrofit2.http.GET
import retrofit2.http.POST
import retrofit2.http.Query
import thedigialex.digitalpet.model.entities.UserDigitalMonster
import thedigialex.digitalpet.model.responses.DigitalMonsterResponse
import thedigialex.digitalpet.model.responses.InventoryResponse
import thedigialex.digitalpet.model.responses.LoginResponse
import thedigialex.digitalpet.model.responses.UserDigitalMonsterResponse

interface AuthApi {
    @FormUrlEncoded
    @POST("api/auth/login")
    suspend fun loginUser(@Field("email") email: String, @Field("password") password: String): Response<LoginResponse>
    @FormUrlEncoded
    @POST("api/auth/register")
    suspend fun registerUser(@Field("name") name: String, @Field("email") email: String, @Field("password") password: String): Response<LoginResponse>

    @GET("api/auth/validate-token")
    suspend fun validateToken(): Response<LoginResponse>

    @GET("api/user/inventory")
    suspend fun getUserInventory(@Query("isEquipped") isEquipped: Boolean? = null): Response<InventoryResponse>

    @POST("api/user/userDigitalMonster/create")
    suspend fun createUserDigitalMonster(@Query("eggId") eggId: Int): Response<UserDigitalMonsterResponse>

    @GET("api/user/userDigitalMonster")
    suspend fun getUserDigitalMonster(): Response<UserDigitalMonsterResponse>

    @POST("api/user/userDigitalMonster/update")
    suspend fun updateUserDigitalMonster(@Body userDigitalMonster: UserDigitalMonster): Response<UserDigitalMonsterResponse>

    @GET("api/digitalMonsters")
    suspend fun fetchDigitalMonsters(
        @Query("eggId") eggId: Int? = null,
        @Query("monsterId") monsterId: Int? = null,
        @Query("battleStage") battleStage: Int? = null,
        @Query("eggReturn") eggReturn: Boolean? = null
    ): Response<DigitalMonsterResponse>
}