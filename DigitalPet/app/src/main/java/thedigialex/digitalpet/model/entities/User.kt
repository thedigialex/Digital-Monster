package thedigialex.digitalpet.model.entities

data class User(
    val id: Long,
    val name: String,
    val email: String,
    val nickname: String? = null
)
