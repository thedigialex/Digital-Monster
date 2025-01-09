package thedigialex.digitalpet.model.responses

import thedigialex.digitalpet.model.entities.User

data class LoginResponse(
    val status: Boolean,
    val message: String,
    val token: String,
    val user: User
)