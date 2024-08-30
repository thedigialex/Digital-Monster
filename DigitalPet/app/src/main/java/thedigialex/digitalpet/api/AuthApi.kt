package thedigialex.digitalpet.api

import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.Field
import retrofit2.http.FormUrlEncoded
import retrofit2.http.GET
import retrofit2.http.POST
import retrofit2.http.Query
import thedigialex.digitalpet.model.requests.NicknameRequest
import thedigialex.digitalpet.model.responses.DigitalMonsterResponse
import thedigialex.digitalpet.model.responses.GenericApiResponse
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
    @POST("api/user/nickname")
    suspend fun updateNickname(@Body nicknameRequest: NicknameRequest): Response<GenericApiResponse>




    @GET("api/user/inventory")
    suspend fun getUserInventory(@Query("isEquipped") isEquipped: Boolean? = null): Response<InventoryResponse>

    @GET("api/user/userDigitalMonster")
    suspend fun getUserDigitalMonster(): Response<UserDigitalMonsterResponse>


    @GET("api/digitalMonster")
    suspend fun fetchDigitalMonster(
        @Query("eggId") eggId: Int,
        @Query("monsterId") monsterId: Int
    ): Response<DigitalMonsterResponse>



    @GET("api/digitalMonsters")
    suspend fun fetchDigitalMonsters(
        @Query("eggId") eggId: Int? = null,
        @Query("monsterId") monsterId: Int? = null,
        @Query("battleStage") battleStage: Int? = null,
        @Query("eggReturn") eggReturn: Boolean? = null
    ): Response<DigitalMonsterResponse>

}