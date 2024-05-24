package thedigialex.digitalpet.api

import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.Field
import retrofit2.http.FormUrlEncoded
import retrofit2.http.GET
import retrofit2.http.POST
import thedigialex.digitalpet.model.responses.GenericApiResponse
import thedigialex.digitalpet.model.responses.InventoryResponse
import thedigialex.digitalpet.model.responses.LoginResponse
import thedigialex.digitalpet.model.requests.NicknameRequest

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

    @GET("api/user/inventories")
    suspend fun getUserInventories(): Response<InventoryResponse>
}