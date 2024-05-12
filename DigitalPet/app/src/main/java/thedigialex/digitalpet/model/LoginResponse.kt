package thedigialex.digitalpet.model

data class LoginResponse(
    val status: Boolean,
    val message: String,
    val token: String,
    val user: User
)

data class User(
    val id: Long,
    val name: String,
    val email: String,
    val nickname: String? = null
)
