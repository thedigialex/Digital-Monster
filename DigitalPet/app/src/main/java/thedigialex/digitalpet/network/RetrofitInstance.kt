package thedigialex.digitalpet.network

import android.content.Context
import okhttp3.OkHttpClient
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import thedigialex.digitalpet.api.AuthApi
import thedigialex.digitalpet.util.TokenManager

object RetrofitInstance {

    private fun getClient(context: Context): Retrofit {

        val baseUrl = context.getString(thedigialex.digitalpet.R.string.base_url)
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
            .baseUrl(baseUrl)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
    }

    fun getApi(context: Context): AuthApi {
        return getClient(context).create(AuthApi::class.java)
    }
}
