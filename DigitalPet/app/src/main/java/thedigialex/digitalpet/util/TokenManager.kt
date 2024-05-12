package thedigialex.digitalpet.util

import android.content.Context
import android.content.SharedPreferences

object TokenManager {
    private const val PREFS_FILE_NAME = "AuthPreferences"
    private const val TOKEN_KEY = "AuthToken"

    fun saveToken(context: Context, token: String) {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        sharedPreferences.edit().putString(TOKEN_KEY, token).apply()
    }

    fun getToken(context: Context): String? {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        return sharedPreferences.getString(TOKEN_KEY, null)
    }

    fun clearToken(context: Context) {
        val sharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        sharedPreferences.edit().remove(TOKEN_KEY).apply()
    }
}
