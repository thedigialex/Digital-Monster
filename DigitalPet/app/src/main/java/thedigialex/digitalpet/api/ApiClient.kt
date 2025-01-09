package thedigialex.digitalpet.api

import android.content.Context
import okhttp3.OkHttpClient
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import thedigialex.digitalpet.util.DataManager

object ApiClient {

    private fun getClient(context: Context): Retrofit {
        val baseUrl = context.getString(thedigialex.digitalpet.R.string.base_url)

        val client = OkHttpClient.Builder()
            .addInterceptor { chain ->
                val token = DataManager.getToken(context)
                val requestBuilder = chain.request().newBuilder()
                requestBuilder.addHeader("Authorization", "Bearer $token")
                requestBuilder.addHeader("X-Api-Key", "5f4143c3b5aa765qwe551wqg8327deb882cf99")

                chain.proceed(requestBuilder.build())
            }
            .build()

        return Retrofit.Builder()
            .baseUrl(baseUrl)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
    }

    fun getApi(context: Context): ApiService {
        return getClient(context).create(ApiService::class.java)
    }
}
