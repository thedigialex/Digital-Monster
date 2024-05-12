package thedigialex.digitalpet.network

import android.content.Context
import okhttp3.OkHttpClient
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import thedigialex.digitalpet.api.AuthApi
import thedigialex.digitalpet.util.TokenManager

object RetrofitInstance {
    private const val BASE_URL = "http://10.0.2.2:8000/"

    private fun getClient(context: Context): Retrofit {
        val client = OkHttpClient.Builder()
            .addInterceptor { chain ->
                val token = TokenManager.getToken(context)
                val requestBuilder = chain.request().newBuilder()
                if (token != null) {
                    requestBuilder.addHeader("Authorization", "Bearer $token")
                }
                chain.proceed(requestBuilder.build())
            }
            .build()

        return Retrofit.Builder()
            .baseUrl(BASE_URL)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
    }

    fun getApi(context: Context): AuthApi {
        return getClient(context).create(AuthApi::class.java)
    }
}
